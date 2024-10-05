<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Genre;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class IndexGenreController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function __invoke(): AnonymousResourceCollection
    {
        $genres = Cache::remember('genres', 60 * 60 * 24, function () {
            return Genre::all();
        });

        return GenreResource::collection($genres);
    }
}
