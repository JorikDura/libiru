<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Publisher;

use App\Models\Image;
use App\Models\Publisher;

final readonly class DeletePublisherAction
{
    public function __invoke(Publisher $publisher): void
    {
        /** @var Image $image */
        $image = $publisher->image()->first();

        $image->delete();

        $publisher->delete();
    }
}
