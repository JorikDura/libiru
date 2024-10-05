<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Book;

use App\Actions\Images\DeleteImageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Image\DeleteImageRequest;
use App\Models\Book;
use Illuminate\Http\Response;

class DeleteBookImageController extends Controller
{
    /**
     * @param  Book  $book
     * @param  DeleteImageAction  $action
     * @param  DeleteImageRequest  $request
     * @return Response
     */
    public function __invoke(
        Book $book,
        DeleteImageAction $action,
        DeleteImageRequest $request
    ): Response {
        $action(
            model: $book,
            request: $request
        );

        return response()->noContent();
    }
}
