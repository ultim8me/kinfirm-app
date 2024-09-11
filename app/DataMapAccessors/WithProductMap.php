<?php

namespace App\DataMapAccessors;

use App\DataMaps\ProductMap;

/**
 * Trait WithProductMap
 */
trait WithProductMap
{
    private ?ProductMap $productMap = null;

    public function getProductMap(): ?ProductMap
    {
        return $this->productMap ?? app(ProductMap::class);
    }
}
