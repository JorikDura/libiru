<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Person\Comment;

use App\Models\Person;
use Illuminate\Http\Request;

final readonly class IndexPersonCommentAction
{
    public function __invoke(
        Person $person,
        Request $request
    ) {
        return $person
            ->comments()
            ->with(['images', 'user'])
            ->paginate()
            ->appends($request->query());
    }
}
