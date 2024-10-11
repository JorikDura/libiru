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
        private UpdatePublisherRequest $request,
        private StoreImageAction $storeImageAction
    ) {
    }

    /**
     * @param  Publisher  $publisher
     * @return Publisher
     * @throws ReflectionException
     */
    public function __invoke(Publisher $publisher): Publisher
    {
        $publisherData = $this->request->safe()->except('image');

        $publisher->update($publisherData);

        unset($publisherData);

        $this->request->whenHas('image', function (UploadedFile $image) use ($publisher) {
            $publisher->image()->first()?->delete();

            $this->storeImageAction->store(
                file: $image,
                model: $publisher
            );
        });

        return $publisher->load('image');
    }
}
