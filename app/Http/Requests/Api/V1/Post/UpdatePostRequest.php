<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Post;

use App\Rules\ImageCounterRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    private const int MAX_IMAGE_COUNT = 10;

    public function rules(): array
    {
        return [
            "title" => ["nullable", "string", 'max:48'],
            "text" => ["nullable", "string", 'max:512'],
            "images" => [
                "nullable",
                "array",
                "max:".self::MAX_IMAGE_COUNT,
                new ImageCounterRule(
                    model: $this->route('post'),
                    max: self::MAX_IMAGE_COUNT
                )
            ],
            "images.*" => ["image", "mimes:jpeg,png,jpg", "max:2048"],
            "related_books" => ["nullable", "array"],
            "related_books.*" => ["int", "exists:books,id"],
            "related_people" => ["nullable", "array"],
            "related_people.*" => ["int", "exists:people,id"],
            "tags" => ["nullable", "array", "min:1", "max:8"],
            "tags.*" => ["string", "max:24"],
        ];
    }
}
