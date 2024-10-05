<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Publisher;

use App\Actions\Api\V1\Publisher\StorePublisherAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Publisher\StorePublisherRequest;
use App\Http\Resources\Api\V1\PublisherResource;
use ReflectionException;

class StorePublisherController extends Controller
{
    /**
     * @param  StorePublisherAction  $action
     * @param  StorePublisherRequest  $request
     * @return PublisherResource
     * @throws ReflectionException
     */
    public function __invoke(
        StorePublisherAction $action,
        StorePublisherRequest $request
    ): PublisherResource {
        $publisher = $action($request);

        return PublisherResource::make($publisher);
    }
}
