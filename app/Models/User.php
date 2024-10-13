<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Notifications\EmailVerifyCodeNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class
        ];
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new EmailVerifyCodeNotification());
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function peopleSubscribes(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Person::class,
            table: 'person_subscribers',
            foreignPivotKey: 'user_id',
            relatedPivotKey: 'person_id'
        );
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Book::class,
            table: 'user_book_list',
            foreignPivotKey: 'user_id',
            relatedPivotKey: 'book_id'
        );
    }

    public function isAdministrator(): bool
    {
        return $this->role == UserRole::ADMIN;
    }

    public function isModerator(): bool
    {
        return $this->role == UserRole::MODERATOR;
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return "users.$this->id";
    }
}
