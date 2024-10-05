<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Publisher;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class ShowPublisherAction
{
    public function __invoke(int $publisherId): Publisher|Model
    {
        return QueryBuilder::for(Publisher::class)
            ->allowedIncludes('books')
            ->with('image')
            ->findOrFail($publisherId);
    }
}
