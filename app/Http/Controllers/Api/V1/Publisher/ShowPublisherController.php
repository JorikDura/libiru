<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Publisher;

use App\Actions\Api\V1\Publisher\ShowPublisherAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PublisherResource;

class ShowPublisherController extends Controller
{
    /**
     * @param  int  $publisherId
     * @param  ShowPublisherAction  $action
     * @return PublisherResource
     */
    public function __invoke(
        int $publisherId,
        ShowPublisherAction $action
    ): PublisherResource {
        $publisher = $action($publisherId);

        return PublisherResource::make($publisher);
    }
}
