<?php

namespace App\DataMapAccessors;

use App\DataMaps\SizeMap;

/**
 * Trait WithSizeMap
 */
trait WithSizeMap
{
    private ?SizeMap $sizeMap = null;

    public function getSizeMap(): ?SizeMap
    {
        return $this->sizeMap ?? app(SizeMap::class);
    }
}
