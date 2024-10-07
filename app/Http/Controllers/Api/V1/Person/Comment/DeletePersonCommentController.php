<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person\Comment;

use App\Actions\Api\V1\Comment\DeleteCommentAction;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Person;
use Illuminate\Http\Response;
use ReflectionException;

class DeletePersonCommentController extends Controller
{
    /**
     * @param  Person  $person
     * @param  Comment  $comment
     * @param  DeleteCommentAction  $action
     * @return Response
     * @throws ReflectionException
     */
    public function __invoke(
        Person $person,
        Comment $comment,
        DeleteCommentAction $action
    ): Response {
        $action($comment);

        return response()->noContent();
    }
}
