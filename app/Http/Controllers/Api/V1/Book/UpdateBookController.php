<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Api\V1\Book\UpdateBookAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BookResource;
use App\Models\Book;
use ReflectionException;

class UpdateBookController extends Controller
{
    /**
     * @param  Book  $book
     * @param  UpdateBookAction  $action
     * @return BookResource
     * @throws ReflectionException
     */
    public function __invoke(
        Book $book,
        UpdateBookAction $action,
    ): BookResource {
        $book = $action($book);

        return BookResource::make($book);
    }
}
