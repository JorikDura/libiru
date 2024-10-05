<?php

declare(strict_types=1);

namespace App\Actions\Images;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image as InterventionImage;
use ReflectionClass;
use ReflectionException;

final readonly class StoreImageAction
{
    private const int DEFAULT_MIN_HEIGHT = 500;
    private const int DEFAULT_NAME_SIZE = 6;
    private const string DEFAULT_FILE_EXTENSION = '.jpg';
    private const string DEFAULT_PATH = 'images/';

    /**
     * @param  UploadedFile  $file
     * @param  ?string  $path
     * @param  int  $id
     * @param  string  $type
     * @param  string|null  $name
     * @return ?Image
     * @throws ReflectionException
     */
    public function store(
        UploadedFile $file,
        int $id,
        string $type,
        ?string $path = null,
        ?string $name = null,
    ): ?Image {
        $image = InterventionImage::read($file);

        $shouldScale = $image->height() > self::DEFAULT_MIN_HEIGHT;

        $path ??= $this->getPath($type);

        /** @var array{original: string, preview: string} $names */
        $names = $this->transformName(
            name: $name,
            path: $path,
            shouldScale: $shouldScale
        );

        $dbImage = Image::create([
            'imageable_id' => $id,
            'imageable_type' => $type,
            'original_image' => $names['original'],
            'preview_image' => $names['preview']
        ]);

        $this->storeImageToDisk(
            names: $names,
            image: $image
        );

        return $dbImage;
    }

    /**
     * @param  array  $files
     * @param  int  $id
     * @param  string  $type
     * @param  ?string  $path
     * @param  ?string  $name
     * @return void
     * @throws ReflectionException
     */
    public function storeMany(
        array $files,
        int $id,
        string $type,
        ?string $path = null,
        ?string $name = null,
    ): void {
        $dbImagesData = [];
        $convertedImages = [];

        foreach ($files as $file) {
            $image = InterventionImage::read($file);

            $shouldScale = $image->height() > self::DEFAULT_MIN_HEIGHT;

            $path ??= $this->getPath($type);

            /** @var array{original: string, preview: string} $names */
            $names = $this->transformName(
                name: $name,
                path: $path,
                shouldScale: $shouldScale
            );

            $dbImagesData[] = [
                'imageable_id' => $id,
                'imageable_type' => $type,
                'original_image' => $names['original'],
                'preview_image' => $names['preview']
            ];
            $convertedImages[] = $image;
        }

        unset($files);

        Image::insert($dbImagesData);

        for ($i = 0; $i < count($dbImagesData); $i++) {
            $this->storeImageToDisk(
                names: [
                    'original' => $dbImagesData[$i]['original_image'],
                    'preview' => $dbImagesData[$i]['preview_image']
                ],
                image: $convertedImages[$i]
            );
        }
    }

    /**
     *
     * @param  ?string  $name
     * @param  string  $path
     * @param  bool  $shouldScale
     * @return array
     */
    private function transformName(
        ?string $name,
        string $path,
        bool $shouldScale
    ): array {
        if (is_null($name)) {
            $name = Str::random(length: self::DEFAULT_NAME_SIZE);
        }

        $name .= '-'.time();

        return [
            'original' => "$path$name".self::DEFAULT_FILE_EXTENSION,
            'preview' => $shouldScale
                ? "$path$name-scaled".self::DEFAULT_FILE_EXTENSION
                : null
        ];
    }

    /**
     * @param  array  $names
     * @param  mixed  $image
     * @return void
     */
    private function storeImageToDisk(
        array $names,
        mixed $image,
    ): void {
        /** @var UploadedFile $imageJpg */
        $imageJpg = $image->toJpeg();

        Storage::disk('public')
            ->put(
                path: $names['original'],
                contents: $imageJpg
            );

        if (!is_null($names['preview'])) {
            /** @var UploadedFile $imageJpg */
            $imageJpg = $image->scale(height: self::DEFAULT_MIN_HEIGHT)
                ->toJpeg();

            Storage::disk('public')
                ->put(
                    path: $names['preview'],
                    contents: $imageJpg
                );
        }
    }

    /**
     * @throws ReflectionException
     */
    private function getPath(string $type): string
    {
        return self::DEFAULT_PATH.Str::of(
            string: (new ReflectionClass($type))->getShortName()
        )->plural()->lower()->toString().'/';
    }
}
