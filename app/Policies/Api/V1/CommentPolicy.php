<?php

declare(strict_types=1);

namespace App\Policies\Api\V1;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdministrator() || $user->isModerator()) {
            return true;
        }

        return null;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id ||
            $this->isUserCommentsOnHisOwnProfile($user, $comment);
    }

    private function isUserCommentsOnHisOwnProfile(User $user, Comment $comment): bool
    {
        return $comment->commentable_type == User::class &&
            $comment->commentable_id == $user->id;
    }
}
