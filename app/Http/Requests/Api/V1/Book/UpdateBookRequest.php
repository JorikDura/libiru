<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Book;

use App\Rules\ImageCounterRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'publisher_id' => ['nullable', 'integer', 'exists:publishers,id'],
            'name' => ['nullable', 'string', 'max:48'],
            'russian_name' => ['nullable', 'string', 'max:48'],
            'description' => ['nullable', 'string', 'max:512'],
            'russian_description' => ['nullable', 'string', 'max:512'],
            'publish_date' => ['nullable', 'date'],
            'images' => ['nullable', 'array', 'max:8', new ImageCounterRule($this->route('book'))],
            'images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'authors' => ['nullable', 'array', 'min:1'],
            'authors.*' => ['nullable', 'integer', 'exists:people,id'],
            'translators' => ['nullable', 'array', 'min:1'],
            'translators.*' => ['nullable', 'integer', 'exists:people,id'],
            'genres' => ['nullable', 'array', 'min:1'],
            'genres.*' => ['nullable', 'integer', 'exists:genres,id']
        ];
    }
}
