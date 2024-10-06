<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Actions\Api\V1\Person\DeletePersonAction;
use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Response;
use ReflectionException;

class DeletePersonController extends Controller
{
    /**
     * @param  Person  $person
     * @param  DeletePersonAction  $action
     * @return Response
     * @throws ReflectionException
     */
    public function __invoke(
        Person $person,
        DeletePersonAction $action
    ): Response {
        $action($person);

        return response()->noContent();
    }
}
