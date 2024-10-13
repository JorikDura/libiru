<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Api\V1\Book\IndexBookListAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BookStatusResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexBookStatusController extends Controller
{
    public function __invoke(
        Book $book,
        IndexBookListAction $action
    ): AnonymousResourceCollection {
        $lists = $action($book);

        return BookStatusResource::collection($lists);
    }
}
