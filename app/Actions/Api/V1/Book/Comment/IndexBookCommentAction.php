<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book\Comment;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class IndexBookCommentAction
{
    /**
     * @param  Book  $book
     * @param  Request  $request
     * @return LengthAwarePaginator
     */
    public function __invoke(
        Book $book,
        Request $request
    ): LengthAwarePaginator {
        return $book
            ->comments()
            ->with([
                'images',
                'user:id,name' => ['image']
            ])
            ->paginate()
            ->appends($request->query());
    }
}
