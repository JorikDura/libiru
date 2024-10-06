<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Publisher;

use App\Actions\Api\V1\Publisher\DeletePublisherAction;
use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Response;
use ReflectionException;

class DeletePublisherController extends Controller
{
    /**
     * @param  Publisher  $publisher
     * @param  DeletePublisherAction  $action
     * @return Response
     * @throws ReflectionException
     */
    public function __invoke(
        Publisher $publisher,
        DeletePublisherAction $action
    ): Response {
        $action($publisher);

        return response()->noContent();
    }
}
