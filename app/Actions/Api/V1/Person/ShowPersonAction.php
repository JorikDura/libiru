<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Person;

use App\Models\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class ShowPersonAction
{
    public function __invoke(int $personId): Person|Model
    {
        return QueryBuilder::for(Person::class)
            ->allowedIncludes(
                includes: AllowedInclude::callback(
                    name: 'books',
                    callback: function (BelongsToMany $query) {
                        $query->select([
                            'id',
                            'publisher_id',
                            'name',
                            'russian_name'
                        ])->with('images');
                    }
                )
            )
            ->with('images')
            ->findOrFail($personId);
    }
}
