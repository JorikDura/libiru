<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Genre;

use App\Actions\Api\V1\Genre\IndexGenreAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GenreResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexGenreController extends Controller
{
    /**
     * @param  IndexGenreAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(IndexGenreAction $action): AnonymousResourceCollection
    {
        $genres = $action();

        return GenreResource::collection($genres);
    }
}
