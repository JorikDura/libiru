<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment;

use App\Actions\Api\V1\Comment\DeleteCommentAction;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Response;
use ReflectionException;

class DeleteUserCommentController extends Controller
{
    /**
     * @param  User  $user
     * @param  Comment  $comment
     * @param  DeleteCommentAction  $action
     * @return Response
     * @throws ReflectionException
     */
    public function __invoke(
        User $user,
        Comment $comment,
        DeleteCommentAction $action
    ): Response {
        $action($comment);

        return response()->noContent();
    }
}
