<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Api\V1\Book\ShowBookAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BookResource;

class ShowBookController extends Controller
{
    /**
     * @param  int  $bookId
     * @param  ShowBookAction  $action
     * @return BookResource
     */
    public function __invoke(
        int $bookId,
        ShowBookAction $action
    ): BookResource {
        $book = $action($bookId);

        return BookResource::make($book);
    }
}
