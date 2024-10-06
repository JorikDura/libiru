<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "text",
        "user_id"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function books(): MorphToMany
    {
        return $this->morphedByMany(
            related: Book::class,
            name: 'relatable',
            table: 'post_related'
        );
    }

    public function people(): MorphToMany
    {
        return $this->morphedByMany(
            related: Person::class,
            name: 'relatable',
            table: 'post_related'
        );
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function loadFullPost(): self
    {
        return $this->load([
            'images',
            'tags',
            'people' => fn (MorphToMany $builder) => $builder->select([
                'id',
                'name',
                'russian_name',
            ])->with('image'),
            'books' => fn (MorphToMany $builder) => $builder->select([
                'id',
                'name',
                'russian_name'
            ])->with('image'),
            'user' => fn (BelongsTo $builder) => $builder->select([
                'id',
                'name',
                'role'
            ])->with(['image'])
        ]);
    }
}
