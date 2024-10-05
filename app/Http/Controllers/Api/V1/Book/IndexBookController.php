<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Api\V1\Book\IndexBookAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Book\IndexBookRequest;
use App\Http\Resources\Api\V1\BookResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexBookController extends Controller
{
    /**
     * @param  IndexBookRequest  $request
     * @param  IndexBookAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        IndexBookAction $action,
        IndexBookRequest $request,
    ): AnonymousResourceCollection {
        $books = $action($request);

        return BookResource::collection($books);
    }
}
