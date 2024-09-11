<?php

namespace App\Http\Resources;


trait WithRelatedResources
{
    public function getRelatedResources(): array
    {
        return [
            'city' => CityResource::make($this->whenLoaded('city')),
            'product' => ProductResource::make($this->whenLoaded('product')),
            'size' => SizeResource::make($this->whenLoaded('size')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'cities' => CityResource::collection($this->whenLoaded('cities')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
