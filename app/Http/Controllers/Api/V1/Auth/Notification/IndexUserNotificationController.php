<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth\Notification;

use App\Actions\Api\V1\Auth\Notification\IndexUserNotificationAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NotificationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexUserNotificationController extends Controller
{
    /**
     * @param  IndexUserNotificationAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(IndexUserNotificationAction $action): AnonymousResourceCollection
    {
        $notifications = $action();

        return NotificationResource::collection($notifications);
    }
}
