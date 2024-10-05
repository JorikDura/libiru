<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'text' => $this->faker->text(),
            'commentable_id' => $this->faker->uuid(),
            'commentable_type' => $this->faker->word(),
        ];
    }
}
