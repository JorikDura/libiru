<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth\Notification;

use App\Http\Requests\Api\V1\Auth\Notification\NotificationMarkAsReadRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final readonly class NotificationMarkAsReadAction
{
    public function __invoke(
        #[CurrentUser] User $user,
        NotificationMarkAsReadRequest $request
    ): void {
        $notification = $user->notifications()->findOrFail($request->validated('id'));

        $notification->markAsRead();
    }
}
