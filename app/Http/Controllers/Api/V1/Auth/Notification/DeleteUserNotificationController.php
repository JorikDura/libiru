<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth\Notification;

use App\Actions\Api\V1\Auth\Notification\DeleteUserNotificationAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DeleteUserNotificationController extends Controller
{
    /**
     * @param  DeleteUserNotificationAction  $action
     * @return Response
     */
    public function __invoke(DeleteUserNotificationAction $action): Response
    {
        $action();

        return response()->noContent();
    }
}
