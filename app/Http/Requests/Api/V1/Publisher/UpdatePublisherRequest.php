<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Publisher;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublisherRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:48'],
            'description' => ['nullable', 'string', 'max:512'],
            'russian_description' => ['nullable', 'string', 'max:512'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }
}
