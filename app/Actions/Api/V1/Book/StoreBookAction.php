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
        private StoreBookRequest $request
    ) {
    }

    /**
     * @return Book
     */
    public function __invoke(): Book
    {
        return DB::transaction(function () {
            $bookData = $this->request->safe()->except([
                'images',
                'authors',
                'translators',
                'genres'
            ]);

            $book = Book::create($bookData);

            unset($bookData);

            /** @var array $authors */
            $authors = $this->request->validated('authors');

            $book->authors()->attach($authors);

            unset($authors);

            $this->request->whenHas('translators', function ($translators) use ($book) {
                $book->translators()->attach(
                    id: $translators,
                    attributes: [
                        'role' => PersonRole::TRANSLATOR
                    ]
                );
            });

            $this->request->whenHas('genres', function (array $genres) use ($book) {
                $book->genres()->attach($genres);
            });

            $this->request->whenHas('images', function (array $images) use ($book) {
                $this->storeImageAction->storeMany(
                    files: $images,
                    model: $book
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
