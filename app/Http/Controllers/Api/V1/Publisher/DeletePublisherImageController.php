<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Publisher;

use App\Actions\Images\DeleteImageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Image\DeleteImageRequest;
use App\Models\Publisher;
use Illuminate\Http\Response;

class DeletePublisherImageController extends Controller
{
    /**
     * @param  Publisher  $publisher
     * @param  DeleteImageAction  $action
     * @param  DeleteImageRequest  $request
     * @return Response
     */
    public function __invoke(
        Publisher $publisher,
        DeleteImageAction $action,
        DeleteImageRequest $request
    ): Response {
        $action(
            model: $publisher,
            request: $request
        );

        return response()->noContent();
    }
}
