<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class HardTestsSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = Publisher::factory(30)
            ->create();

        $books = Book::factory(30000)->make([
            'publisher_id' => $publishers->random()->id
        ]);

        $books->chunk(1000)->each(function ($books) {
            Book::insert($books->toArray());
        });
    }
}
