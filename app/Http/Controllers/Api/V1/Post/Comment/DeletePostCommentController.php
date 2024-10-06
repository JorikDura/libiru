<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Comment;

use App\Actions\Api\V1\Post\Comment\DeletePostCommentAction;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Response;
use ReflectionException;

class DeletePostCommentController extends Controller
{
    /**
     * @param  Post  $post
     * @param  Comment  $comment
     * @param  DeletePostCommentAction  $action
     * @return Response
     * @throws ReflectionException
     */
    public function __invoke(
        Post $post,
        Comment $comment,
        DeletePostCommentAction $action
    ) {
        $action($comment);

        return response()->noContent();
    }
}
