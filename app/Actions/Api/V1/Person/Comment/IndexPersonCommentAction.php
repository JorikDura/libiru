<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Person\Comment;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class IndexPersonCommentAction
{
    /**
     * @param  Person  $person
     * @param  Request  $request
     * @return LengthAwarePaginator
     */
    public function __invoke(
        Person $person,
        Request $request
    ): LengthAwarePaginator {
        return $person
            ->comments()
            ->with(['images', 'user'])
            ->paginate()
            ->appends($request->query());
    }
}
