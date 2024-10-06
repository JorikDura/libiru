<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Images\DeleteImageActionByIdAction;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Response;

class DeleteBookImageController extends Controller
{
    /**
     * @param  Book  $book
     * @param  DeleteImageActionByIdAction  $action
     * @return Response
     */
    public function __invoke(
        Book $book,
        DeleteImageActionByIdAction $action
    ): Response {
        $action($book);

        return response()->noContent();
    }
}
