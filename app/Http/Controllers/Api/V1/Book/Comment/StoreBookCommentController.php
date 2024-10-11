<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book\Comment;

use App\Actions\Api\V1\Comment\StoreCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\Book;
use ReflectionException;

class StoreBookCommentController extends Controller
{
    /**
     * @param  Book  $book
     * @param  StoreCommentAction  $action
     * @return CommentResource
     * @throws ReflectionException
     */
    public function __invoke(
        Book $book,
        StoreCommentAction $action
    ): CommentResource {
        $comment = $action($book);

        return CommentResource::make($comment);
    }
}
