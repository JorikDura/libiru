<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Book;

use App\Actions\Images\StoreImageAction;
use App\Enums\PersonRole;
use App\Http\Requests\Api\V1\Book\StoreBookRequest;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

final readonly class StoreBookAction
{
    public function __construct(
        private StoreImageAction $storeImageAction,
    ) {
    }

    /**
     * @param  StoreBookRequest  $request
     * @return Book
     */
    public function __invoke(
        StoreBookRequest $request
    ): Book {
        return DB::transaction(function () use ($request) {
            $bookData = $request->safe()->except([
                'images',
                'authors',
                'translators',
                'genres'
            ]);

            $book = Book::create($bookData);

            unset($bookData);

            /** @var array $authors */
            $authors = $request->validated('authors');

            $book->authors()->attach($authors);

            unset($authors);

            $request->whenHas('translators', function ($translators) use ($book) {
                $book->translators()->attach(
                    id: $translators,
                    attributes: [
                        'role' => PersonRole::TRANSLATOR
                    ]
                );
            });

            $request->whenHas('genres', function (array $genres) use ($book) {
                $book->genres()->attach($genres);
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
                'authors:id,name,russian_name',
                'translators:id,name,russian_name',
                'publisher:id,name',
                'genres'
            ]);
        });
    }
}
