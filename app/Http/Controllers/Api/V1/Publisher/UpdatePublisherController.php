<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Publisher;

use App\Actions\Api\V1\Publisher\UpdatePublisherAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Publisher\UpdatePublisherRequest;
use App\Http\Resources\Api\V1\PublisherResource;
use App\Models\Publisher;
use ReflectionException;

class UpdatePublisherController extends Controller
{
    /**
     * @param  Publisher  $publisher
     * @param  UpdatePublisherAction  $action
     * @param  UpdatePublisherRequest  $request
     * @return PublisherResource
     * @throws ReflectionException
     */
    public function __invoke(
        Publisher $publisher,
        UpdatePublisherAction $action,
        UpdatePublisherRequest $request
    ): PublisherResource {
        $publisher = $action(
            publisher: $publisher,
            request: $request
        );

        return PublisherResource::make($publisher);
    }
}
