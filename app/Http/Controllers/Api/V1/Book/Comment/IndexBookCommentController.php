<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book\Comment;

use App\Actions\Api\V1\Book\Comment\IndexBookCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexBookCommentController extends Controller
{
    /**
     * @param  Book  $book
     * @param  IndexBookCommentAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Book $book,
        IndexBookCommentAction $action
    ): AnonymousResourceCollection {
        $comments = $action($book);

        return CommentResource::collection($comments);
    }
}
