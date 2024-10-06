<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Person\Comment;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class IndexPersonCommentAction
{
    public function __construct(
        private Request $request
    ) {
    }

    /**
     * @param  Person  $person
     * @return LengthAwarePaginator
     */
    public function __invoke(Person $person): LengthAwarePaginator
    {
        return $person
            ->comments()
            ->with(['images', 'user'])
            ->paginate()
            ->appends($this->request->query());
    }
}
