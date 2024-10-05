<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class GenreSeeder extends Seeder
{
    private const int DEFAULT_CHUNK_LENGTH = 30;

    public function run(): void
    {
        $genresJson = File::json('public/genres.json');

        foreach (array_chunk(array: $genresJson, length: self::DEFAULT_CHUNK_LENGTH) as $value) {
            Genre::insert($value);
        }
    }
}
