<?php

namespace App\Services;

use App\Constants\ErrorMessages;
use App\DataAccess\CacheDatabase\ResolvableKeyCache;
use App\DataMapAccessors\WithCityMap;
use App\DataTransferObjects\CityDto;
use App\DataTransferObjects\StockDto;
use App\Exceptions\ServiceException;
use App\Http\Resources\CityResource;
use App\Models\PrimaryDatabase\City;
use App\Models\PrimaryDatabase\Stock;
use App\Patterns\Log\LogException;
use App\Patterns\Log\LogMessage;
use App\Patterns\MethodResponses\CreateMethodResponse;
use App\Patterns\MethodResponses\FetchMethodResponse;
use App\Patterns\Resolver\KeyResolver;
use App\Patterns\Resolver\WithKeyResolver;
use App\ServiceAccessors\WithCacheService;

class CityService
{
    use WithCityMap;
    use WithCacheService;
    use WithKeyResolver;

    /**
     * @param CityDto $cityDto
     * @return CreateMethodResponse
     */
    public function createCity(CityDto $cityDto): CreateMethodResponse
    {
        try {

            $city = new City();
            if (!$this->getCityMap()->fromCityDto($cityDto, $city)) {
                throw new ServiceException(ErrorMessages::CITY_MAP_FROM_DTO_FAILED);
            }

            if (!$city->save()) {
                throw new ServiceException();
            }

            $cityDto->setId($city->id);

            $methodResponse = $this->getCacheService()->create(
                $cityDto->getCacheKey(),
                $city
            );

            if ($methodResponse->isFailure()) {
                throw new ServiceException();
            }

        } catch (\Exception|ServiceException $exception) {

            return CreateMethodResponse::error([
                new LogMessage(ErrorMessages::CITY_CREATE_FAILED),
                new LogException($exception)
            ]);
        }

        return CreateMethodResponse::created();
    }


    /**
     * @param CityDto $cityDto
     * @return FetchMethodResponse
     */
    public function fetchByName(CityDto $cityDto): FetchMethodResponse
    {
        try {

            $this->getKeyResolver()->resolveId($cityDto);
            $methodResponse = $this->getCacheService()->fetchValueByKey(
                $cityDto->getCacheKey(),
                $city
            );

            if ($methodResponse->isNotFound()) {
                $city = City::where(City::NAME, $cityDto->getName())->first();
            }

            if (!$city) {
                return FetchMethodResponse::notFound();
            }

            if (!$this->getCityMap()->toCityDto($city, $cityDto)) {
                throw new ServiceException(ErrorMessages::CITY_MAP_TO_DTO_FAILED);
            }

        } catch (\Exception|ServiceException $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::CITY_FETCH_BY_NAME_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param $cities
     * @return FetchMethodResponse
     */
    public function fetchAll(&$cities): FetchMethodResponse
    {
        try {

            $cities = CityResource::collection((new City)->paginate());

            if ($cities->isEmpty()) {
                return FetchMethodResponse::notFound();
            }

        } catch (\Exception $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::CITIES_FETCH_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param CityDto $cityDto
     * @param StockDto $stockDto
     * @return CreateMethodResponse
     */
    public function addProductStock(CityDto $cityDto, StockDto $stockDto): CreateMethodResponse
    {
        try {

            $city = new City();
            if (!$this->getCityMap()->fromCityDto($cityDto, $city)) {
                throw new ServiceException(ErrorMessages::CITY_MAP_FROM_DTO_FAILED);
            }

            $city->products()->attach($stockDto->getProductId(), [Stock::QUANTITY => $stockDto->getQuantity()]);

        } catch (\Exception|ServiceException $exception) {

            return CreateMethodResponse::error([
                new LogMessage(ErrorMessages::ADD_PRODUCT_STOCK_FAILED),
                new LogException($exception)
            ]);
        }

        return CreateMethodResponse::created();
    }

    /**
     * @return KeyResolver
     */
    private function initializeKeyResolver(): KeyResolver
    {
        return new KeyResolver(
            [City::NAME],
            new ResolvableKeyCache('city'),
            new City
        );
    }
}
