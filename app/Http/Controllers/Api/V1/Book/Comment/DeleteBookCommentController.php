<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book\Comment;

use App\Actions\Api\V1\Comment\DeleteCommentAction;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Comment;
use Illuminate\Http\Response;
use ReflectionException;

class DeleteBookCommentController extends Controller
{
    /**
     * @param  Book  $book
     * @param  Comment  $comment
     * @param  DeleteCommentAction  $action
     * @return Response
     * @throws ReflectionException
     */
    public function __invoke(
        Book $book,
        Comment $comment,
        DeleteCommentAction $action
    ) {
        $action($comment);

        return response()->noContent();
    }
}
