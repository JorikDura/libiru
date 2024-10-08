<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Book;

use App\Enums\BookListStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddBookToUserListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(BookListStatus::class)],
            'favorite' => ['nullable', 'boolean'],
            'score' => ['nullable', 'numeric', 'min:0', 'max:10'],
        ];
    }
}
