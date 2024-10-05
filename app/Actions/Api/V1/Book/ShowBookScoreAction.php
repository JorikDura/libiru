<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final readonly class ShowBookScoreAction
{
    public function __invoke(int $bookId): Collection
    {
        return DB::table('user_book_list')
            ->select('user_book_list.score')
            ->addSelect(DB::raw('count("user_book_list".*)'))
            ->whereRaw(
                sql: 'user_book_list.book_id = ?',
                bindings: [$bookId]
            )
            ->groupBy('user_book_list.score')
            ->orderByDesc('user_book_list.score')
            ->get();
    }
}
