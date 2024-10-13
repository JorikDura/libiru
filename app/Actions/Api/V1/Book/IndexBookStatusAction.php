<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final readonly class IndexBookStatusAction
{
    public function __invoke(int $bookId): Collection
    {
        return DB::table('user_book_list')
            ->selectRaw('count(status)')
            ->addSelect('status')
            ->where('book_id', $bookId)
            ->groupBy('status')
            ->get();
    }
}
