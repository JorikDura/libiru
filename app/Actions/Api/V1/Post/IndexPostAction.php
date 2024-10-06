<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexPostAction
{
    public function __construct(
        private Request $request
    ) {
    }

    public function __invoke(): LengthAwarePaginator
    {
        return QueryBuilder::for(Post::class)
            ->select([
                'id',
                'user_id',
                'title',
                'created_at',
                'updated_at'
            ])
            ->with([
                'image',
                'tags',
                'user' => fn (BelongsTo $builder) => $builder->select([
                    'id',
                    'name',
                    'role'
                ])->with(['image'])
            ])
            ->paginate()
            ->appends($this->request->query());
    }
}
