<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Person;

use App\Models\Person;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final readonly class SubscribePersonAction
{
    public function __construct(
        #[CurrentUser] private User $user
    ) {
    }

    public function __invoke(Person $person): void
    {
        $person->userSubscribers()->attach($this->user);
    }
}
