<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PersonRole;
use App\Observers\BookObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[ObservedBy(BookObserver::class)]
class Book extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'publisher_id',
        'name',
        'russian_name',
        'description',
        'russian_description',
        'publish_date'
    ];

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->latestOfMany();
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Person::class)
            ->wherePivot('role', PersonRole::AUTHOR->value);
    }

    public function translators(): BelongsToMany
    {
        return $this->belongsToMany(Person::class)
            ->wherePivot('role', PersonRole::TRANSLATOR->value);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'user_book_list',
            foreignPivotKey: 'book_id',
            relatedPivotKey: 'user_id'
        );
    }

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(
            related: Post::class,
            name: 'relatable',
            table: 'post_related'
        );
    }
}
