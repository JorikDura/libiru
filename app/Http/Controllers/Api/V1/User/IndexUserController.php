<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Actions\Api\V1\User\IndexUserAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexUserController extends Controller
{
    /**
     * @param  Request  $request
     * @param  IndexUserAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Request $request,
        IndexUserAction $action
    ): AnonymousResourceCollection {
        $users = $action($request);

        return UserResource::collection($users);
    }
}
