<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book;

use App\Http\Requests\Api\V1\Book\IndexBookRequest;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class IndexBookAction
{
    public function __construct(
        private IndexBookRequest $request
    ) {
    }

    /**
     * @return LengthAwarePaginator
     */
    public function __invoke(): LengthAwarePaginator
    {
        /** @var ?User $user */
        $user = auth('sanctum')->user();

        $userId = $this->request->validated('user_id') ?? $user?->id;

        unset($user);

        $status = $this->request->validated('list_status');

        $isFavourite = $this->request->validated('is_favorite', false);

        return QueryBuilder::for(Book::class)
            ->allowedFilters([
                'publisher.name',
                AllowedFilter::callback(
                    name: 'book.name',
                    callback: function (Builder $query, string $value) {
                        $query->whereRaw(
                            sql: '"books"."name" LIKE ?',
                            bindings: ["%$value%"]
                        )->orWhereRaw(
                            sql: '"books"."russian_name" LIKE ?',
                            bindings: ["%$value%"]
                        );
                    }
                ),
                AllowedFilter::callback(
                    name: 'person.name',
                    callback: fn (Builder $query, $value) => $query->whereHas(
                        relation: 'people',
                        callback: function (Builder $query) use ($value) {
                            $query->whereRaw(
                                sql: '"people"."name" LIKE ?',
                                bindings: ["%$value%"]
                            )->orWhereRaw(
                                sql: '"people"."russian_name" LIKE ?',
                                bindings: ["%$value%"]
                            );
                        }
                    )
                )
            ])
            ->with([
                'image',
                'genres',
                'publisher:id,name'
            ])
            ->selectSub(
                query: function ($query) {
                    $query->from('user_book_list')
                        ->selectRaw('round(avg("user_book_list"."score"), 2)')
                        ->whereRaw('books.id = "user_book_list"."book_id"');
                },
                as: 'total_score'
            )
            ->addSelect('books.*')
            ->when(
                value: ($isFavourite && !is_null($userId)),
                callback: fn (Builder $query) => $query->whereHas(
                    relation: 'users',
                    callback: fn (Builder $query) => $query->where('user_book_list.is_favorite', true)
                )
            )->when(
                value: (!is_null($userId) && !is_null($status)),
                callback: fn (Builder $query) => $query->whereHas(
                    relation: 'users',
                    callback: fn (Builder $query) => $query->where([
                        'user_book_list.status' => $status,
                        'user_book_list.user_id' => $userId
                    ])
                )
            )->paginate()->appends($this->request->query());
    }
}
