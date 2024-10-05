<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Api\V1\Book\DeleteBookAction;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Response;

class DeleteBookController extends Controller
{
    /**
     * @param  Book  $book
     * @param  DeleteBookAction  $action
     * @return Response
     */
    public function __invoke(
        Book $book,
        DeleteBookAction $action
    ): Response {
        $action($book);

        return response()->noContent();
    }
}
