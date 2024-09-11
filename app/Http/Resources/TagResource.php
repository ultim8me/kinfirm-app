<?php

namespace App\Http\Resources;

use App\Models\PrimaryDatabase\Tag;
use Illuminate\Http\Request;

class TagResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Tag $this */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'products_count' => $this->whenNotNull($this->products_count),
            ...$this->getTimestamps(),
        ];
    }
}
