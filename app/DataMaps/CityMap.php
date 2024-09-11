<?php

namespace App\DataMaps;


use App\DataTransferObjects\CityDto;
use App\Models\PrimaryDatabase\City;

/**
 * Class CityMap
 */
class CityMap
{
    /**
     * @param City|null $city
     * @param CityDto $cityDto
     * @return bool
     */
    public function toCityDto(?City $city, CityDto $cityDto): bool
    {
        try {

            $cityDto
                ->setId($city[City::ID])
                ->setName($city[City::NAME])
            ;

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    public function toCityDtos(array $citys, array &$cityDtos): bool
    {
        foreach ($citys as $city) {
            $cityDto = new CityDto();
            $this->toCityDto($city, $cityDto);
            $cityDtos[] = $cityDto;
        }
        return true;
    }

    /**
     * @param CityDto $cityDto
     * @param City $city
     * @return bool
     */
    public function fromCityDto(CityDto $cityDto, City $city): bool
    {
        try {

            $city[City::ID] = $cityDto->getId();
            $city[City::NAME] = $cityDto->getName();

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }
}
