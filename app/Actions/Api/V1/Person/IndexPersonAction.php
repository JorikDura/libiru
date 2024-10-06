<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Person;

use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexPersonAction
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
        return QueryBuilder::for(Person::class)
            ->allowedFilters([
                AllowedFilter::callback('name', function (Builder $query, string $value) {
                    $query->whereLike('name', "%$value%")
                        ->orWhereLike('russian_name', "%$value%");
                }),
            ])
            ->with(['image'])
            ->paginate(columns: [
                'id',
                'name',
                'russian_name'
            ])
            ->appends($this->request->query());
    }
}
