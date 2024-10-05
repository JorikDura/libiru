<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Person;

use App\Actions\Images\StoreImageAction;
use App\Http\Requests\Api\V1\Person\StorePersonRequest;
use App\Models\Person;
use ReflectionException;

final readonly class StorePersonAction
{
    public function __construct(
        private StoreImageAction $storeImageAction
    ) {
    }

    /**
     * @param  StorePersonRequest  $request
     * @return Person
     * @throws ReflectionException
     */
    public function __invoke(
        StorePersonRequest $request
    ): Person {
        $personData = $request->safe()->except('images');

        $person = Person::create($personData);

        unset($personData);

        $request->whenHas('images', function (array $images) use ($person) {
            $this->storeImageAction->storeMany(
                files: $images,
                id: $person->id,
                type: Person::class,
            );
        });

        return $person->load(['images']);
    }
}
