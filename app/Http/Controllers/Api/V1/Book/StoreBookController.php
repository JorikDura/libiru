<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Api\V1\Book\StoreBookAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Book\StoreBookRequest;
use App\Http\Resources\Api\V1\BookResource;

class StoreBookController extends Controller
{
    /**
     * @param  StoreBookAction  $action
     * @param  StoreBookRequest  $request
     * @return BookResource
     */
    public function __invoke(
        StoreBookAction $action,
        StoreBookRequest $request
    ): BookResource {
        $book = $action($request);

        return BookResource::make($book);
    }
}
