<?php

namespace App\DataMapAccessors;

use App\DataMaps\CityMap;

/**
 * Trait WithCityMap
 */
trait WithCityMap
{
    private ?CityMap $cityMap = null;

    public function getCityMap(): ?CityMap
    {
        return $this->cityMap ?? app(CityMap::class);
    }
}
