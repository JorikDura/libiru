<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Person;

use App\Rules\ImageCounterRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:48'],
            'russian_name' => ['nullable', 'string', 'max:48'],
            'description' => ['nullable', 'string', 'max:512'],
            'russian_description' => ['nullable', 'string', 'max:512'],
            'birth_date' => ['nullable', 'date'],
            'death_date' => ['nullable', 'date'],
            'images' => ['nullable', 'array', 'min:1', 'max:8', new ImageCounterRule($this->route('person'))],
            'images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }
}
