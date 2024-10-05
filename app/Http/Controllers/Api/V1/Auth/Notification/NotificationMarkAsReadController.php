<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth\Notification;

use App\Actions\Api\V1\Auth\Notification\NotificationMarkAsReadAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\Notification\NotificationMarkAsReadRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Response;

class NotificationMarkAsReadController extends Controller
{
    /**
     * @param  User  $user
     * @param  NotificationMarkAsReadAction  $action
     * @param  NotificationMarkAsReadRequest  $request
     * @return Response
     */
    public function __invoke(
        #[CurrentUser] User $user,
        NotificationMarkAsReadAction $action,
        NotificationMarkAsReadRequest $request
    ): Response {
        $action(
            user: $user,
            request: $request
        );

        return response()->noContent();
    }
}
