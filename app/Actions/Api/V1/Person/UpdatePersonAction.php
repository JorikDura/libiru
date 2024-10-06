<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Person;

use App\Actions\Images\StoreImageAction;
use App\Http\Requests\Api\V1\Person\UpdatePersonRequest;
use App\Models\Person;
use ReflectionException;

final readonly class UpdatePersonAction
{
    public function __construct(
        private UpdatePersonRequest $request,
        private StoreImageAction $storeImageAction
    ) {
    }

    /**
     * @param  Person  $person
     * @return Person
     * @throws ReflectionException
     */
    public function __invoke(Person $person): Person
    {
        $personData = $this->request->safe()->except('images');

        $person->update($personData);

        unset($personData);

        $this->request->whenHas('images', function (array $images) use ($person) {
            $this->storeImageAction->storeMany(
                files: $images,
                id: $person->id,
                type: Person::class,
            );
        });

        return $person->loadMissing(['images']);
    }
}
