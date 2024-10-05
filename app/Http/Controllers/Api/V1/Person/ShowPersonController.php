<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Actions\Api\V1\Person\ShowPersonAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PersonResource;

class ShowPersonController extends Controller
{
    /**
     * @param  int  $personId
     * @param  ShowPersonAction  $action
     * @return PersonResource
     */
    public function __invoke(
        int $personId,
        ShowPersonAction $action
    ): PersonResource {
        $person = $action($personId);

        return PersonResource::make($person);
    }
}
