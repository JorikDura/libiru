<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Publisher;

use App\Actions\Images\DeleteImageAction;
use App\Models\Publisher;
use ReflectionException;

final readonly class DeletePublisherAction
{
    public function __construct(
        private DeleteImageAction $deleteImageAction
    ) {
    }

    /**
     * @param  Publisher  $publisher
     * @return void
     * @throws ReflectionException
     */
    public function __invoke(Publisher $publisher): void
    {
        $this->deleteImageAction->__invoke($publisher);

        $publisher->delete();
    }
}
