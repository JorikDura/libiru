<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $score
 * @property int $count
 */
class BookScoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'score' => $this->score,
            'count' => $this->count
        ];
    }
}
