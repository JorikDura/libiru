<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Person;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:48'],
            'russian_name' => ['required', 'string', 'max:48'],
            'description' => ['nullable', 'string', 'max:512'],
            'russian_description' => ['nullable', 'string', 'max:512'],
            'birth_date' => ['required', 'date'],
            'death_date' => ['nullable', 'date'],
            'images' => ['nullable', 'array', 'min:1', 'max:8'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }
}
