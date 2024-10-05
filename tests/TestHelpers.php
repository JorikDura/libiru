<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Random\RandomException;

class TestHelpers
{
    private const int IMAGE_WIDTH = 600;
    private const int IMAGE_HEIGHT = 600;

    /**
     * @throws RandomException
     */
    public static function randomUploadedFiles(int $min = 2, int $max = 8): array
    {
        $files = [];

        for ($i = 1; $i < random_int($min, $max); $i++) {
            $files[] = static::uploadFile("test_$i.jpg");
        }

        return $files;
    }

    public static function uploadFile(string $name): File
    {
        return UploadedFile::fake()->image(
            name: $name,
            width: self::IMAGE_WIDTH,
            height: self::IMAGE_HEIGHT
        );
    }
}
