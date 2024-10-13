<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Api\V1\Book\AddBookToUserListAction;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Response;

class AddBookToUserListController extends Controller
{
    public function __invoke(
        Book $book,
        AddBookToUserListAction $action
    ): Response {
        $action($book);

        return response()->noContent();
    }
}
