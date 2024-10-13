<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UserRouteServiceProvider extends ServiceProvider
{
    private const string SEARCH_SYMBOL = '+';
    private const string REPLACE_SYMBOL = ' ';

    public function boot(): void
    {
        Route::bind(
            key: 'nickname',
            binder: fn (string $nickname): User => $this->findUserByNickname($nickname)
        );

        Route::bind(
            key: 'nicknameId',
            binder: fn (string $nicknameId): int => $this->findUserByNickname($nicknameId)->id
        );
    }

    private function findUserByNickname(string $nickname): User
    {
        $nickname = str_replace(
            search: self::SEARCH_SYMBOL,
            replace: self::REPLACE_SYMBOL,
            subject: $nickname
        );

        return User::select('id')->where(
            DB::raw('LOWER(nickname)'),
            mb_strtolower($nickname)
        )->firstOrFail();
    }
}
