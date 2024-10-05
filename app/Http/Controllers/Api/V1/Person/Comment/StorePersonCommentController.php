<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person\Comment;

use App\Actions\Api\V1\Comment\StoreCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Comment\StoreCommentRequest;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\Person;
use ReflectionException;

class StorePersonCommentController extends Controller
{
    /**
     * @param  Person  $person
     * @param  StoreCommentAction  $action
     * @param  StoreCommentRequest  $request
     * @return CommentResource
     * @throws ReflectionException
     */
    public function __invoke(
        Person $person,
        StoreCommentAction $action,
        StoreCommentRequest $request
    ): CommentResource {
        $comment = $action(
            commentableId: $person->id,
            commentableType: Person::class,
            request: $request
        );

        return CommentResource::make($comment);
    }
}
