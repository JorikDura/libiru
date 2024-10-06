<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class ShowUserAction
{
    /**
     * @param  int  $userId
     * @return User|Model
     */
    public function __invoke(int $userId): User|Model
    {
        return QueryBuilder::for(User::class)
            ->with(['image'])
            ->findOrFail($userId);
    }
}
