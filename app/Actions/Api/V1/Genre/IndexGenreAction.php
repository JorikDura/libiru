<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Genre;

use App\Models\Genre;
use Illuminate\Support\Facades\Cache;

final readonly class IndexGenreAction
{
    /**
     * @return mixed
     */
    public function __invoke(): mixed
    {
        return Cache::remember(
            key: 'genres',
            ttl: 60 * 60 * 24,
            callback: fn() => Genre::all()
        );
    }
}
