<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Book
 */
class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'russian_name' => $this->russian_name,
            'description' => $this->whenHas('description'),
            'russian_description' => $this->whenHas('russian_description'),
            'image' => ImageResource::make($this->whenLoaded('image')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'authors' => PersonResource::collection($this->whenLoaded('authors')),
            'translators' => PersonResource::collection($this->whenLoaded('translators')),
            'publisher' => PublisherResource::make($this->whenLoaded('publisher')),
            $this->mergeWhen(!$this->relationLoaded('publisher'), [
                'publisher_id' => $this->whenHas('publisher_id')
            ]),
            'genres' => GenreResource::collection($this->whenLoaded('genres')),
            'total_score' => $this->whenHas('total_score'),
            'user_score' => $this->whenHas('score'),
            'is_favorite' => $this->whenHas('is_favorite'),
            'status' => $this->whenHas('status'),
        ];
    }
}
