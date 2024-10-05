<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Actions\Images\DeleteImageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Image\DeleteImageRequest;
use App\Models\Person;
use Illuminate\Http\Response;

class DeletePersonImageController extends Controller
{
    /**
     * @param  Person  $person
     * @param  DeleteImageAction  $action
     * @param  DeleteImageRequest  $request
     * @return Response
     */
    public function __invoke(
        Person $person,
        DeleteImageAction $action,
        DeleteImageRequest $request
    ): Response {
        $action(
            model: $person,
            request: $request
        );

        return response()->noContent();
    }
}
