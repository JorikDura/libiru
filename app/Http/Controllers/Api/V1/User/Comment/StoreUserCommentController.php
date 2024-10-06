<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment;

use App\Actions\Api\V1\Comment\StoreCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\User;
use ReflectionException;

class StoreUserCommentController extends Controller
{
    /**
     * @param  User  $user
     * @param  StoreCommentAction  $action
     * @return CommentResource
     * @throws ReflectionException
     */
    public function __invoke(
        User $user,
        StoreCommentAction $action
    ) {
        $comment = $action(
            commentableId: $user->id,
            commentableType: User::class
        );

        return CommentResource::make($comment);
    }
}
