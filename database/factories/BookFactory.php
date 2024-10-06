<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'russian_name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'russian_description' => $this->faker->text(),
            'publisher_id' => Publisher::factory(),
        ];
    }
}
