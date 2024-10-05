<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'publisher_id' => ['required', 'integer', 'exists:publishers,id'],
            'name' => ['nullable', 'string', 'max:48'],
            'russian_name' => ['required', 'string', 'max:48'],
            'description' => ['nullable', 'string', 'max:512'],
            'russian_description' => ['nullable', 'string', 'max:512'],
            'publish_date' => ['nullable', 'date'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['required', 'integer', 'exists:people,id'],
            'translators' => ['nullable', 'array', 'min:1'],
            'translators.*' => ['required', 'integer', 'exists:people,id'],
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['required', 'integer', 'exists:genres,id']
        ];
    }
}
