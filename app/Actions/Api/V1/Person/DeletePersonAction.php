<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Person;

use App\Models\Person;

final readonly class DeletePersonAction
{
    /**
     * @param  Person  $person
     * @return void
     */
    public function __invoke(Person $person): void
    {
        $images = $person->images()
            ->get();

        $images->each(function ($image) {
            $image->deleteImagesInStorage();
        });

        $person->images()->delete();

        $person->delete();
    }
}
