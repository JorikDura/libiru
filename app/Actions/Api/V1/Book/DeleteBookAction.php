<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book;

use App\Actions\Images\DeleteImageAction;
use App\Models\Book;
use ReflectionException;

final readonly class DeleteBookAction
{
    public function __construct(
        private DeleteImageAction $deleteImageAction
    ) {
    }

    /**
     * @param  Book  $book
     * @return void
     * @throws ReflectionException
     */
    public function __invoke(Book $book): void
    {
        $this->deleteImageAction->__invoke($book);

        $book->delete();
    }
}
