<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Api\V1\Post\IndexPostAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PostResource;

class IndexPostController extends Controller
{
    public function __invoke(IndexPostAction $action)
    {
        $posts = $action();

        return PostResource::collection($posts);
    }
}
