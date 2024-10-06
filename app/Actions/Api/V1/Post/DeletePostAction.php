<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Actions\Images\DeleteImageAction;
use App\Models\Post;
use ReflectionException;

final readonly class DeletePostAction
{
    public function __construct(
        private DeleteImageAction $deleteImageAction,
    ) {
    }

    /**
     * @param  Post  $post
     * @return void
     * @throws ReflectionException
     */
    public function __invoke(Post $post): void
    {
        $this->deleteImageAction->__invoke($post);

        $post->delete();
    }
}
