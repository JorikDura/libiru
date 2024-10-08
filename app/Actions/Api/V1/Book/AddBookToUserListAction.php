<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book;

use App\Http\Requests\Api\V1\Book\AddBookToUserListRequest;
use App\Models\Book;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final readonly class AddBookToUserListAction
{
    public function __construct(
        #[CurrentUser] private User $user,
        private AddBookToUserListRequest $request
    ) {
    }

    public function __invoke(Book $book): void
    {
        $status = $this->request->validated('status');
        $favourite = $this->request->validated('favorite', false);
        $score = $this->request->validated('score');

        $this->user->books()->syncWithPivotValues(
            ids: $book,
            values: [
                'status' => $status,
                'is_favorite' => $favourite,
                'score' => $score
            ],
            detaching: false
        );
    }
}
