<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Actions\Api\V1\Person\SubscribePersonAction;
use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Response;

class SubscribePersonController extends Controller
{
    /**
     * @param  Person  $person
     * @param  SubscribePersonAction  $action
     * @return Response
     */
    public function __invoke(
        Person $person,
        SubscribePersonAction $action
    ) {
        $action($person);

        return response()->noContent();
    }
}
