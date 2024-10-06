<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person\Comment;

use App\Actions\Api\V1\Person\Comment\IndexPersonCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\Person;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexPersonCommentController extends Controller
{
    /**
     * @param  Person  $person
     * @param  IndexPersonCommentAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Person $person,
        IndexPersonCommentAction $action
    ): AnonymousResourceCollection {
        $comments = $action($person);

        return CommentResource::collection($comments);
    }
}
