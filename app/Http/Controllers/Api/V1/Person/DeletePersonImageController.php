<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Actions\Images\DeleteImageActionByIdAction;
use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Response;

class DeletePersonImageController extends Controller
{
    /**
     * @param  Person  $person
     * @param  DeleteImageActionByIdAction  $action
     * @return Response
     */
    public function __invoke(
        Person $person,
        DeleteImageActionByIdAction $action
    ): Response {
        $action($person);

        return response()->noContent();
    }
}
