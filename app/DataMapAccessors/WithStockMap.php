<?php

namespace App\DataMapAccessors;

use App\DataMaps\StockMap;

/**
 * Trait WithStockMap
 */
trait WithStockMap
{
    private ?StockMap $stockMap = null;

    public function getStockMap(): ?StockMap
    {
        return $this->stockMap ?? app(StockMap::class);
    }
}
