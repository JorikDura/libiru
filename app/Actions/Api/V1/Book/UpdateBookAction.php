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
        private StoreImageAction $storeImageAction,
        private UpdateBookRequest $request
    ) {
    }

    /**
     * @param  Book  $book
     * @return Book
     * @throws ReflectionException
     */
    public function __invoke(
        Book $book
    ): Book {
        $bookData = $this->request->safe()->except([
            'images',
            'authors',
            'translators',
            'genres'
        ]);

        $book->update($bookData);

        unset($bookData);

        $this->request->whenHas('authors', function (array $authors) use ($book) {
            $book->authors()->sync($authors);
        });

        $this->request->whenHas('translators', function (array $translators) use ($book) {
            $book->translators()->syncWithPivotValues(
                ids: $translators,
                values: [
                    'role' => PersonRole::TRANSLATOR
                ]
            );
        });

        $this->request->whenHas('genres', function (array $genres) use ($book) {
            $book->genres()->sync($genres);
        });

        $this->request->whenHas('images', function (array $images) use ($book) {
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
