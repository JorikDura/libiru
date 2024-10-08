<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

final readonly class IndexBookListAction
{
    public function __invoke(Book $book): Collection
    {
        return $book
            ->selectRaw('count(status)')
            ->addSelect('status')
            ->join(
                table: 'user_book_list',
                first: 'id',
                operator: '=',
                second: 'book_id'
            )->groupBy('status')
            ->get();
    }
}
