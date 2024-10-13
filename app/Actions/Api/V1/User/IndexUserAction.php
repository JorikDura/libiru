<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexUserAction
{
    public function __construct(
        private Request $request,
    ) {
    }

    /**
     * @return LengthAwarePaginator
     */
    public function __invoke(): LengthAwarePaginator
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters([
                'nickname'
            ])
            ->with(['image'])
            ->paginate()
            ->appends($this->request->query());
    }
}
