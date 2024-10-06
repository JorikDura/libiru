<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post\Comment;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

final readonly class IndexPostCommentAction
{
    public function __construct(
        private Request $request
    ) {
    }

    public function __invoke(Post $post): LengthAwarePaginator
    {
        return $post
            ->comments()
            ->with([
                'images',
                'user:id,name' => ['image']
            ])
            ->paginate()
            ->appends($this->request->query());
    }
}
