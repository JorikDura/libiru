<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Publisher;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexPublisherAction
{
    /**
     * @param  Request  $request
     * @return LengthAwarePaginator
     */
    public function __invoke(Request $request): LengthAwarePaginator
    {
        return QueryBuilder::for(Publisher::class)
            ->allowedFilters('name')
            ->with(['image'])
            ->paginate()
            ->appends($request->query());
    }
}
