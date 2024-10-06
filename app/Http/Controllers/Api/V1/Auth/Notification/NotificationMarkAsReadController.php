<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth\Notification;

use App\Actions\Api\V1\Auth\Notification\NotificationMarkAsReadAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class NotificationMarkAsReadController extends Controller
{
    /**
     * @param  NotificationMarkAsReadAction  $action
     * @return Response
     */
    public function __invoke(NotificationMarkAsReadAction $action): Response
    {
        $action();

        return response()->noContent();
    }
}
