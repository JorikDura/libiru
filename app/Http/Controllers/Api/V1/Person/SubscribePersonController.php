<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Response;

class SubscribePersonController extends Controller
{
    /**
     * @param  Person  $person
     * @param  User  $user
     * @return Response
     */
    public function __invoke(
        Person $person,
        #[CurrentUser] User $user
    ) {
        $person->userSubscribers()->attach($user);

        return response()->noContent();
    }
}
