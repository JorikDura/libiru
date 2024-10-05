<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Random\RandomException;

trait HasCode
{
    protected int $min = 1000;
    protected int $max = 9999;

    /**
     * @throws RandomException
     */
    protected function generateCode(): int
    {
        return random_int($this->min, $this->max);
    }

    /**
     * @throws RandomException
     */
    protected function verificationCode(mixed $notifiable, string $key, int $time = 60): int
    {
        $code = $this->generateCode();

        Cache::forget("email_{$notifiable->getKey()}");

        Cache::remember(
            key: $key."_{$notifiable->getKey()}",
            ttl: Carbon::now()->addMinutes(Config::get(key: 'auth.verification.expire', default: $time)),
            callback: fn () => $code
        );

        return $code;
    }
}
