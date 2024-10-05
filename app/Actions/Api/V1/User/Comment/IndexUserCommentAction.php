<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\User\Comment;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class IndexUserCommentAction
{
    public function __invoke(
        User $user,
        Request $request
    ): LengthAwarePaginator {
        return $user
            ->comments()
            ->with(['user', 'images'])
            ->paginate()
            ->appends($request->query());
    }
}
