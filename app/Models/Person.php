<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'russian_name',
        'description',
        'russian_description',
        'birth_date',
        'death_date'
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'death_date' => 'date',
        ];
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->latestOfMany();
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function userSubscribers(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'person_subscribers',
            foreignPivotKey: 'person_id',
            relatedPivotKey: 'user_id',
        );
    }
}
