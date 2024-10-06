<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth\Notification;

use App\Http\Requests\Api\V1\Auth\Notification\DeleteUserNotificationRequest;
use App\Models\User;

final readonly class DeleteUserNotificationAction
{
    /**
     * @param  User  $user
     * @param  DeleteUserNotificationRequest  $request
     * @return void
     */
    public function __invoke(
        User $user,
        DeleteUserNotificationRequest $request
    ): void {
        $user->notifications()
            ->whereId($request->validated('id'))
            ->delete();
    }
}
