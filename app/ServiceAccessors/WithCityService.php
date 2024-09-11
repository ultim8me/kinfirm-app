<?php
namespace App\ServiceAccessors;

use App\Services\CityService;

trait WithCityService
{
    /**
     * @internal This value is managed by getCityService.
     * Direct access is discouraged.
     *
     * @var ?CityService An CityService object.
     */
    private ?CityService $cityService = null;

    /**
     * Accessor method to get the CityService value.
     *
     * @return CityService An CityService object.
     */
    public function getCityService() : CityService
    {
        return $this->cityService ?? app(CityService::class);
    }
}
