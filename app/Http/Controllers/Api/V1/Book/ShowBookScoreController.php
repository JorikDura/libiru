<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Api\V1\Book\ShowBookScoreAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BookScoreResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShowBookScoreController extends Controller
{
    /**
     * @param  int  $bookId
     * @param  ShowBookScoreAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        int $bookId,
        ShowBookScoreAction $action
    ): AnonymousResourceCollection {
        $test = $action($bookId);

        return BookScoreResource::collection($test);
    }
}
