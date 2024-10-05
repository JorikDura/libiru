<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Publisher;

use App\Actions\Api\V1\Publisher\IndexPublisherAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PublisherResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexPublisherController extends Controller
{
    /**
     * @param  Request  $request
     * @param  IndexPublisherAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Request $request,
        IndexPublisherAction $action
    ): AnonymousResourceCollection {
        $publishers = $action($request);

        return PublisherResource::collection($publishers);
    }
}
