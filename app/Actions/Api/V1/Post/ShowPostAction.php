<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

final readonly class ShowPostAction
{
    /**
     * @param  int  $postId
     * @return Post
     */
    public function __invoke(int $postId): Post
    {
        return Post::with([
            'images',
            'tags',
            'people' => fn (MorphToMany $builder) => $builder->select([
                'id',
                'name',
                'russian_name',
            ])->with('image'),
            'books' => fn (MorphToMany $builder) => $builder->select([
                'id',
                'name',
                'russian_name'
            ])->with('image'),
            'user' => fn (BelongsTo $builder) => $builder->select([
                'id',
                'name',
                'role'
            ])->with(['image'])
        ])->findOrFail($postId);
    }
}
