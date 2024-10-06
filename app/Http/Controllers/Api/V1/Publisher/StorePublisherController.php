<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Publisher;

use App\Actions\Api\V1\Publisher\StorePublisherAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PublisherResource;
use ReflectionException;

class StorePublisherController extends Controller
{
    /**
     * @param  StorePublisherAction  $action
     * @return PublisherResource
     * @throws ReflectionException
     */
    public function __invoke(
        StorePublisherAction $action
    ): PublisherResource {
        $publisher = $action();

        return PublisherResource::make($publisher);
    }
}
