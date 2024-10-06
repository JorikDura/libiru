<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Publisher;

use App\Actions\Images\DeleteImageActionByIdAction;
use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Response;

class DeletePublisherImageController extends Controller
{
    /**
     * @param  Publisher  $publisher
     * @param  DeleteImageActionByIdAction  $action
     * @return Response
     */
    public function __invoke(
        Publisher $publisher,
        DeleteImageActionByIdAction $action
    ): Response {
        $action($publisher);

        return response()->noContent();
    }
}
