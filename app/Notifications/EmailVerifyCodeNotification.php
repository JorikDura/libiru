<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Random\RandomException;

class EmailVerifyCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use HasCode;

    public const string VERIFICATION_KEY = 'email';

    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * @throws RandomException
     */
    public function toMail($notifiable): MailMessage
    {
        $code = $this->verificationCode(
            notifiable: $notifiable,
            key: self::VERIFICATION_KEY
        );

        return $this->buildMailMessage($code);
    }

    protected function buildMailMessage($code): MailMessage
    {
        return (new MailMessage())
            ->subject('Verify Email Address')
            ->line('Your verification code:')
            ->line($code)
            ->line('If you did not create an account, no further action is required.');
    }
}
