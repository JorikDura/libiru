<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Api\V1\Book\IndexBookAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BookResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexBookController extends Controller
{
    /**
     * @param  IndexBookAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(IndexBookAction $action): AnonymousResourceCollection
    {
        $books = $action();

        return BookResource::collection($books);
    }
}
