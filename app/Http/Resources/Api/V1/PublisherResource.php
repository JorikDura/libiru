<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Publisher
 */
class PublisherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->whenHas('description'),
            'russian_description' => $this->whenHas('russian_description'),
            'image' => ImageResource::make($this->whenLoaded('image')),
        ];
    }
}
