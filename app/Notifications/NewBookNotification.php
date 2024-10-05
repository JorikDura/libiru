<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Book;
use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewBookNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    private const string NOTIFICATION_NAME = 'NewBookNotification';

    public function __construct(
        private readonly Book $book,
        private readonly Person $person,
    ) {
    }

    public function via(): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(): array
    {
        return [
            'book' => [
                'id' => $this->book->id,
                'name' => $this->book->name,
                'russian_name' => $this->book->russian_name
            ],
            'person' => [
                'id' => $this->person->id,
                'name' => $this->person->name,
                'russian_name' => $this->person->russian_name
            ]
        ];
    }

    public function toBroadcast(): BroadcastMessage
    {
        return new BroadcastMessage([
            'book' => [
                'id' => $this->book->id,
                'name' => $this->book->name,
                'russian_name' => $this->book->russian_name
            ],
            'person' => [
                'id' => $this->person->id,
                'name' => $this->person->name,
                'russian_name' => $this->person->russian_name
            ]
        ]);
    }

    public function databaseType(): string
    {
        return self::NOTIFICATION_NAME;
    }

    public function broadcastType(): string
    {
        return self::NOTIFICATION_NAME;
    }
}
