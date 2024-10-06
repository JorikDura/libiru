<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "title" => ["required", "string", 'max:48'],
            "text" => ["required", "string", 'max:512'],
            "images" => ["nullable", "array", "max:10"],
            "images.*" => ["image", "mimes:jpeg,png,jpg", "max:2048"],
            "related_books" => ["nullable", "array"],
            "related_books.*" => ["int", "exists:books,id"],
            "related_people" => ["nullable", "array"],
            "related_people.*" => ["int", "exists:people,id"],
            "tags" => ["required", "array", "min:1", "max:8"],
            "tags.*" => ["string", "max:24"],
        ];
    }
}
