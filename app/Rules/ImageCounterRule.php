<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final readonly class ImageCounterRule implements ValidationRule
{
    public function __construct(
        private mixed $model,
        private int $max = 8
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $inputImagesCount = count($value);
        $dbBookImagesCount = $this->model->images()->count();

        if ($this->max < ($inputImagesCount + $dbBookImagesCount)) {
            $fail('validation.custom.image_count')->translate([
                'attribute' => $attribute,
                'max' => $this->max
            ]);
        }
    }
}
