<?php

declare(strict_types=1);

namespace App\Actions\Images;

use App\Models\Image;
use ReflectionClass;
use ReflectionException;

final readonly class DeleteImageAction
{
    private const string ERROR_MESSAGE = "There's no such methods as 'images' or 'image' for %s";

    /**
     * Delete images
     * If there is no such methods as "images" or "image"
     * Exception will be thrown
     * @param  object  $model
     * @return void
     * @throws ReflectionException
     */
    public function __invoke(object $model): void
    {
        $reflection = new ReflectionClass($model);

        if ($reflection->hasMethod('images')) {
            $images = $model->images()->get();

            $images?->each(function (Image $image) {
                $image->deleteImagesInStorage();
            });

            if (!is_null($images)) {
                $model->images()->delete();
            }

            return;
        }

        if ($reflection->hasMethod('image')) {
            /** @var ?Image $image */
            $image = $model->image()->first();

            $image?->delete();

            return;
        }

        throw new ReflectionException(
            message: sprintf(
                format: self::ERROR_MESSAGE,
                values: $reflection->getShortName()
            )
        );
    }
}
