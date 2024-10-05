<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth\Notification;

use App\Actions\Api\V1\Auth\Notification\IndexUserNotificationAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NotificationResource;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexUserNotificationController extends Controller
{
    /**
     * @param  Request  $request
     * @param  User  $user
     * @param  IndexUserNotificationAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Request $request,
        #[CurrentUser] User $user,
        IndexUserNotificationAction $action
    ): AnonymousResourceCollection {
        $notifications = $action(
            request: $request,
            user: $user
        );

        return NotificationResource::collection($notifications);
    }
}
