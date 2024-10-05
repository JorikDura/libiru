<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'russian_name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'russian_description' => $this->faker->text(),
            'birth_date' => Carbon::now(),
            'death_date' => Carbon::now(),
        ];
    }
}
