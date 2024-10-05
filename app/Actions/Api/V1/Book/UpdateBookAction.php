<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book;

use App\Actions\Images\StoreImageAction;
use App\Enums\PersonRole;
use App\Http\Requests\Api\V1\Book\UpdateBookRequest;
use App\Models\Book;
use ReflectionException;

final readonly class UpdateBookAction
{
    public function __construct(
        private StoreImageAction $storeImageAction
    ) {
    }

    /**
     * @param  Book  $book
     * @param  UpdateBookRequest  $request
     * @return Book
     * @throws ReflectionException
     */
    public function __invoke(
        Book $book,
        UpdateBookRequest $request
    ): Book {
        $bookData = $request->safe()->except([
            'images',
            'authors',
            'translators',
            'genres'
        ]);

        $book->update($bookData);

        unset($bookData);

        $request->whenHas('authors', function (array $authors) use ($book) {
            $book->authors()->sync($authors);
        });

        $request->whenHas('translators', function (array $translators) use ($book) {
            $book->translators()->syncWithPivotValues(
                ids: $translators,
                values: [
                    'role' => PersonRole::TRANSLATOR
                ]
            );
        });

        $request->whenHas('genres', function (array $genres) use ($book) {
            $book->genres()->sync($genres);
        });

        $request->whenHas('images', function (array $images) use ($book) {
            $this->storeImageAction->storeMany(
                files: $images,
                id: $book->id,
                type: Book::class,
            );
        });

        return $book->load([
            'images',
            'authors',
            'translators',
            'publisher',
            'genres'
        ]);
    }
}
