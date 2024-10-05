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
        private StoreImageAction $storeImageAction
    ) {
    }

    /**
     * @param  StorePublisherRequest  $request
     * @return Publisher
     * @throws ReflectionException
     */
    public function __invoke(
        StorePublisherRequest $request
    ): Publisher {
        $publisherData = $request->safe()->except('image');

        $publisher = Publisher::create($publisherData);

        unset($publisherData);

        $request->whenHas('image', function (UploadedFile $image) use ($publisher) {
            $this->storeImageAction->store(
                file: $image,
                id: $publisher->id,
                type: Publisher::class
            );
        });

        return $publisher->load('image');
    }
}
