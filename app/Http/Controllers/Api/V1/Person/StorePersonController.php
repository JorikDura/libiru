<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Actions\Api\V1\Person\StorePersonAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PersonResource;
use ReflectionException;

class StorePersonController extends Controller
{
    /**
     * @param  StorePersonAction  $action
     * @return PersonResource
     * @throws ReflectionException
     */
    public function __invoke(StorePersonAction $action): PersonResource
    {
        $person = $action();

        return PersonResource::make($person);
    }
}
