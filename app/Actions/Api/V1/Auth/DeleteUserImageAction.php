<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Auth;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

final readonly class DeleteUserImageAction
{
    /**
     * @param  User  $user
     * @return void
     */
    public function __invoke(User $user): void
    {
        $image = $user->image()->first() ?? abort(
            code: Response::HTTP_NOT_FOUND,
            message: __('messages.no_image')
        );

        $image->delete();
    }
}
