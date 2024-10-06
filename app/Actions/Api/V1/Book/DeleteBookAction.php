<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book;

use App\Models\Book;

final readonly class DeleteBookAction
{
    /**
     * @param  Book  $book
     * @return void
     */
    public function __invoke(Book $book): void
    {
        $images = $book->images()->get();

        $images->each(function ($image) {
            $image->deleteImagesInStorage();
        });

        $book->images()->delete();

        $book->delete();
    }
}
