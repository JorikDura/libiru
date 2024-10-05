<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth\Notification;

use App\Actions\Api\V1\Auth\Notification\DeleteUserNotificationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\Notification\DeleteUserNotificationRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Response;

class DeleteUserNotificationController extends Controller
{
    /**
     * @param  User  $user
     * @param  DeleteUserNotificationAction  $action
     * @param  DeleteUserNotificationRequest  $request
     * @return Response
     */
    public function __invoke(
        #[CurrentUser] User $user,
        DeleteUserNotificationAction $action,
        DeleteUserNotificationRequest $request
    ): Response {
        $action(
            user: $user,
            request: $request
        );

        return response()->noContent();
    }
}
