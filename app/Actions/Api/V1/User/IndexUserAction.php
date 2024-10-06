<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexUserAction
{
    /**
     * @param  Request  $request
     * @return LengthAwarePaginator
     */
    public function __invoke(Request $request): LengthAwarePaginator
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters([
                'name',
            ])
            ->with(['image'])
            ->paginate()
            ->appends($request->query());
    }
}
