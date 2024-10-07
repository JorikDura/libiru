<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Comment;

use App\Actions\Images\DeleteImageAction;
use App\Models\Comment;
use ReflectionException;

final readonly class DeleteCommentAction
{
    public function __construct(
        private DeleteImageAction $deleteImageAction
    ) {
    }

    /**
     * Delete comment with images
     * @param  Comment  $comment
     * @return void
     * @throws ReflectionException
     */
    public function __invoke(Comment $comment): void
    {
        $this->deleteImageAction->__invoke($comment);

        $comment->delete();
    }
}
