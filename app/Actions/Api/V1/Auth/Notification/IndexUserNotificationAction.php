<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth\Notification;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

final readonly class IndexUserNotificationAction
{
    public function __construct(
        private Request $request,
        #[CurrentUser] private User $user,
    ) {
    }

    /**
     * @return LengthAwarePaginator
     */
    public function __invoke(): LengthAwarePaginator
    {
        return $this->user
            ->notifications()
            ->paginate(columns: [
                'id',
                'type',
                'data',
                'read_at',
                'created_at'
            ])
            ->appends($this->request->query());
    }
}
