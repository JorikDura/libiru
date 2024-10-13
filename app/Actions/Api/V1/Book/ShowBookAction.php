<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class ShowBookAction
{
    /**
     * @param  int  $bookId
     * @return Book|Model
     */
    public function __invoke(int $bookId): Book|Model
    {
        /** @var ?User $user */
        $user = auth('sanctum')->user();

        return QueryBuilder::for(Book::class)
            ->allowedIncludes([
                AllowedInclude::relationship(name: 'publisher', internalName: 'publisher:id,name'),
                AllowedInclude::relationship(name: 'authors', internalName: 'authors:id,name'),
                AllowedInclude::relationship(name: 'translators', internalName: 'translators:id,name')
            ])
            ->with(['images', 'genres'])
            ->select('books.*')
            ->selectSub(
                query: function ($query) use ($bookId) {
                    $query->selectRaw('round(avg("user_book_list"."score"), 2)')
                        ->from('user_book_list')
                        ->whereRaw(
                            sql: '"user_book_list"."book_id" = ?',
                            bindings: [$bookId]
                        );
                },
                as: 'total_score'
            )->selectSub(
                query: function ($query) use ($bookId) {
                    $query->selectRaw('count(is_favorite)')
                        ->from('user_book_list')
                        ->whereRaw(
                            sql: '"user_book_list"."book_id" = ? AND "user_book_list"."is_favorite" = true',
                            bindings: [$bookId]
                        );
                },
                as: 'favorite_count'
            )->when(
                value: !is_null($user),
                callback: function (Builder $query) use ($bookId, $user) {
                    $query->addSelect([
                        'user_book_list.score',
                        'user_book_list.is_favorite',
                        'user_book_list.status'
                    ])->leftJoinSub(
                        query: function ($query) use ($bookId, $user) {
                            $query->select('*')
                                ->from('user_book_list')
                                ->whereRaw(
                                    sql: '"user_id" = ?',
                                    bindings: [$user->id]
                                )->whereRaw(
                                    sql: '"book_id" = ?',
                                    bindings: [$bookId]
                                );
                        },
                        as: 'user_book_list',
                        first: function (JoinClause $join) {
                            $join->on(
                                first: 'user_book_list.book_id',
                                operator: '=',
                                second: 'books.id'
                            );
                        }
                    );
                }
            )->findOrFail($bookId);
    }
}
