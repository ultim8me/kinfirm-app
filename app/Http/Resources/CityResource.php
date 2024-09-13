<?php

namespace App\Http\Resources;

use App\Models\PrimaryDatabase\City;
use Illuminate\Http\Request;

class CityResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var City $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            ...$this->getRelatedResources(),
            ...$this->getTimestamps(),
        ];
    }
}
