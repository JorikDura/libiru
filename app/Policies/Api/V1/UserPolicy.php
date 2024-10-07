<?php

declare(strict_types=1);

namespace App\Policies\Api\V1;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * All actions for books allowed only for admins and moderators
     * @param  User  $user
     * @return bool
     */
    public function role(User $user): bool
    {
        return $user->isAdministrator() || $user->isModerator();
    }
}
