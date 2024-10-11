<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Publisher;

use App\Actions\Images\StoreImageAction;
use App\Http\Requests\Api\V1\Publisher\StorePublisherRequest;
use App\Models\Publisher;
use Illuminate\Http\UploadedFile;
use ReflectionException;

final readonly class StorePublisherAction
{
    public function __construct(
        private StoreImageAction $storeImageAction,
        private StorePublisherRequest $request
    ) {
    }

    /**
     * @return Publisher
     * @throws ReflectionException
     */
    public function __invoke(): Publisher
    {
        $publisherData = $this->request->safe()->except('image');

        $publisher = Publisher::create($publisherData);

        unset($publisherData);

        $this->request->whenHas('image', function (UploadedFile $image) use ($publisher) {
            $this->storeImageAction->store(
                file: $image,
                model: $publisher
            );
        });

        return $publisher->load('image');
    }
}
