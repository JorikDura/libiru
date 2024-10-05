<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment;

use App\Actions\Api\V1\User\Comment\IndexUserCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexUserCommentController extends Controller
{
    /**
     * @param  User  $user
     * @param  Request  $request
     * @param  IndexUserCommentAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        User $user,
        Request $request,
        IndexUserCommentAction $action
    ): AnonymousResourceCollection {
        $comments = $action(
            user: $user,
            request: $request
        );

        return CommentResource::collection($comments);
    }
}
