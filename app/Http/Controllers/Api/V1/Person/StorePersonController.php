<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Actions\Api\V1\Person\StorePersonAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Person\StorePersonRequest;
use App\Http\Resources\Api\V1\PersonResource;

class StorePersonController extends Controller
{
    /**
     * @param  StorePersonAction  $action
     * @param  StorePersonRequest  $request
     * @return PersonResource
     * @throws \ReflectionException
     */
    public function __invoke(
        StorePersonAction $action,
        StorePersonRequest $request
    ): PersonResource {
        $person = $action($request);

        return PersonResource::make($person);
    }
}
