<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\DeleteUserImageAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserImageController extends Controller
{
    /**
     * @param  User  $user
     * @param  DeleteUserImageAction  $action
     * @return Response
     */
    public function __invoke(
        #[CurrentUser] User $user,
        DeleteUserImageAction $action
    ): Response {
        $action($user);

        return response()->noContent();
    }
}
