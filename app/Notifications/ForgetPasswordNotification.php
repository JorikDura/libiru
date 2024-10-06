<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Traits\HasCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Random\RandomException;

class ForgetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use HasCode;

    public const string VERIFICATION_KEY = 'forget_password';

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

    protected function buildMailMessage(int $code): MailMessage
    {
        return (new MailMessage())
            ->subject('Forget Password')
            ->line('Hello!')
            ->line('Your verification code for restoring password is:')
            ->line($code)
            ->line('If you did not restore password, no further action is required.');
    }
}
