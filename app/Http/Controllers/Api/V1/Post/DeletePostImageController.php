<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Images\DeleteImageActionByIdAction;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Response;

class DeletePostImageController extends Controller
{
    /**
     * @param  Post  $post
     * @param  DeleteImageActionByIdAction  $action
     * @return Response
     */
    public function __invoke(
        Post $post,
        DeleteImageActionByIdAction $action
    ): Response {
        $action($post);

        return response()->noContent();
    }
}
