<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth\Notification;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

final readonly class IndexUserNotificationAction
{
    /**
     * @param  Request  $request
     * @param  User  $user
     * @return LengthAwarePaginator
     */
    public function __invoke(
        Request $request,
        User $user
    ): LengthAwarePaginator {
        return $user
            ->notifications()
            ->paginate(columns: [
                'id',
                'type',
                'data',
                'read_at',
                'created_at'
            ])
            ->appends($request->query());
    }
}
