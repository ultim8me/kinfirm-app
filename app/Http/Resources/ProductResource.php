<?php

namespace App\Http\Resources;

use App\Models\PrimaryDatabase\Product;
use Illuminate\Http\Request;

class ProductResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Product $this */
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'photo_url' => $this->photo_url,
            'description' => $this->description,
            ...$this->getTimestamps(),
        ];
    }
}
