<?php

namespace App\Http\Resources;

use App\Models\PrimaryDatabase\Size;
use Illuminate\Http\Request;

class SizeResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Size $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            ...$this->getRelatedResources(),
            ...$this->getTimestamps(),
        ];
    }
}
