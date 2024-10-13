<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Comment;

use App\Actions\Api\V1\Post\Comment\IndexPostCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexPostCommentController extends Controller
{
    public function __invoke(
        Post $post,
        IndexPostCommentAction $action
    ): AnonymousResourceCollection {
        $comments = $action($post);

        return PostResource::collection($comments);
    }
}
