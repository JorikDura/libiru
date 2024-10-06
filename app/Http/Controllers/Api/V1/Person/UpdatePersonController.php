<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Actions\Api\V1\Person\UpdatePersonAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PersonResource;
use App\Models\Person;
use ReflectionException;

class UpdatePersonController extends Controller
{
    /**
     * @param  Person  $person
     * @param  UpdatePersonAction  $action
     * @return PersonResource
     * @throws ReflectionException
     */
    public function __invoke(
        Person $person,
        UpdatePersonAction $action
    ): PersonResource {
        $person = $action($person);

        return PersonResource::make($person);
    }
}
