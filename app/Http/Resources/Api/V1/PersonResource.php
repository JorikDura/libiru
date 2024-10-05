<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Person
 */
class PersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'russian_name' => $this->russian_name,
            'birth_date' => $this->whenHas('birth_date'),
            'death_date' => $this->whenHas('death_date'),
            'description' => $this->whenHas('description'),
            'russian_description' => $this->whenHas('russian_description'),
            'image' => ImageResource::make($this->whenLoaded('image')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'books' => BookResource::collection($this->whenLoaded('books')),
        ];
    }
}
