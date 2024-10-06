<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Tag;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexTagAction
{
    public function __construct(
        private Request $request
    ) {
    }

    /**
     * @return LengthAwarePaginator
     */
    public function __invoke(): LengthAwarePaginator
    {
        return QueryBuilder::for(Tag::class)
            ->allowedFilters(['name'])
            ->paginate()
            ->appends($this->request->query());
    }
}
