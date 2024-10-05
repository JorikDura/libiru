<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Book;

use App\Enums\BookListStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'int', 'exists:users,id'],
            'list_status' => ['nullable', Rule::enum(BookListStatus::class)],
            'is_favorite' => ['nullable', 'boolean']
        ];
    }
}
