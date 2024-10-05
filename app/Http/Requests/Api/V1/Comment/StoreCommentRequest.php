<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required_without:images', 'string', 'max:512'],
            'images' => ['nullable', 'array', 'max:4'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ];
    }
}
