<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Book;
use App\Models\Person;
use App\Notifications\NewBookNotification;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Notification;

class BookObserver implements ShouldHandleEventsAfterCommit
{
    private const int DEFAULT_CHUNK_SIZE = 100;

    public function created(Book $book): void
    {
        $book->people()
            ->get()
            ->each(function (Person $person) use ($book) {
                $person->userSubscribers()
                    ->select('users.id')
                    ->chunk(
                        count: self::DEFAULT_CHUNK_SIZE,
                        callback: fn ($users) => Notification::send($users, new NewBookNotification($book, $person))
                    );
            });
    }
}
