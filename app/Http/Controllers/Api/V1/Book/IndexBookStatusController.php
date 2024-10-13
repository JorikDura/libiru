<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Api\V1\Book\IndexBookStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BookStatusResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexBookStatusController extends Controller
{
    public function __invoke(
        int $bookId,
        IndexBookStatusAction $action
    ): AnonymousResourceCollection {
        $lists = $action($bookId);

        return BookStatusResource::collection($lists);
    }
}
