<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Publisher;

use App\Actions\Api\V1\Publisher\UpdatePublisherAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PublisherResource;
use App\Models\Publisher;
use ReflectionException;

class UpdatePublisherController extends Controller
{
    /**
     * @param  Publisher  $publisher
     * @param  UpdatePublisherAction  $action
     * @return PublisherResource
     * @throws ReflectionException
     */
    public function __invoke(
        Publisher $publisher,
        UpdatePublisherAction $action
    ): PublisherResource {
        $publisher = $action($publisher);

        return PublisherResource::make($publisher);
    }
}
