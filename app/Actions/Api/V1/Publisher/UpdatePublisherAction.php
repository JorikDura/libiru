<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Publisher;

use App\Actions\Images\StoreImageAction;
use App\Http\Requests\Api\V1\Publisher\UpdatePublisherRequest;
use App\Models\Publisher;
use Illuminate\Http\UploadedFile;
use ReflectionException;

final readonly class UpdatePublisherAction
{
    public function __construct(
        private StoreImageAction $storeImageAction
    ) {
    }

    /**
     * @param  Publisher  $publisher
     * @param  UpdatePublisherRequest  $request
     * @return Publisher
     * @throws ReflectionException
     */
    public function __invoke(
        Publisher $publisher,
        UpdatePublisherRequest $request
    ): Publisher {
        $publisherData = $request->safe()->except('image');

        $publisher->update($publisherData);

        unset($publisherData);

        $request->whenHas('image', function (UploadedFile $image) use ($publisher) {
            $publisher->image()->first()?->delete();

            $this->storeImageAction->store(
                file: $image,
                id: $publisher->id,
                type: Publisher::class
            );
        });

        return $publisher->load('image');
    }
}
