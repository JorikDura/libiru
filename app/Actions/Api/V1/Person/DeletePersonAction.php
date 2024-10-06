<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Person;

use App\Actions\Images\DeleteImageAction;
use App\Models\Person;
use ReflectionException;

final readonly class DeletePersonAction
{
    public function __construct(
        private DeleteImageAction $deleteImageAction
    ) {
    }

    /**
     * @param  Person  $person
     * @return void
     * @throws ReflectionException
     */
    public function __invoke(Person $person): void
    {
        $this->deleteImageAction->__invoke($person);

        $person->delete();
    }
}
