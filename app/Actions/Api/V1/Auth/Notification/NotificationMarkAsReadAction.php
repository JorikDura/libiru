<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth\Notification;

use App\Http\Requests\Api\V1\Auth\Notification\NotificationMarkAsReadRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final readonly class NotificationMarkAsReadAction
{
    public function __construct(
        #[CurrentUser] private User $user,
        private NotificationMarkAsReadRequest $request
    ) {
    }

    /**
     * @return void
     */
    public function __invoke(): void
    {
        $notification = $this->user->notifications()->findOrFail($this->request->validated('id'));

        $notification->markAsRead();
    }
}
