<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth\Notification;

use App\Http\Requests\Api\V1\Auth\Notification\NotificationMarkAsReadRequest;
use App\Models\User;

final readonly class NotificationMarkAsReadAction
{
    /**
     * @param  User  $user
     * @param  NotificationMarkAsReadRequest  $request
     * @return void
     */
    public function __invoke(
        User $user,
        NotificationMarkAsReadRequest $request
    ): void {
        $notification = $user->notifications()->findOrFail($request->validated('id'));

        $notification->markAsRead();
    }
}
