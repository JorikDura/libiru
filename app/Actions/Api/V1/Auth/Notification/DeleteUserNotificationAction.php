<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth\Notification;

use App\Http\Requests\Api\V1\Auth\Notification\DeleteUserNotificationRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final readonly class DeleteUserNotificationAction
{
    public function __construct(
        #[CurrentUser] private User $user,
        private DeleteUserNotificationRequest $request
    ) {
    }

    /**
     * @return void
     */
    public function __invoke(): void
    {
        $this->user->notifications()
            ->whereId($this->request->validated('id'))
            ->delete();
    }
}
