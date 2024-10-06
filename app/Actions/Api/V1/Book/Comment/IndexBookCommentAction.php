<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book\Comment;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class IndexBookCommentAction
{
    public function __construct(
        private Request $request
    ) {
    }

    /**
     * @param  Book  $book
     * @return LengthAwarePaginator
     */
    public function __invoke(Book $book): LengthAwarePaginator
    {
        return $book
            ->comments()
            ->with([
                'images',
                'user:id,name' => ['image']
            ])
            ->paginate()
            ->appends($this->request->query());
    }
}
