<?php

namespace App\DataTransferObjects;

trait WithCity
{
    protected ?CityDto $cityDto  = null;

    public function getCityDto(): ?CityDto
    {
        return $this->cityDto;
    }

    public function setCityDto(?CityDto $cityDto): static
    {
        $this->cityDto = $cityDto;

        if (method_exists($this,'setCityId')) {
            $this->setCityId($cityDto->getId());
        }

        return $this;
    }

}
