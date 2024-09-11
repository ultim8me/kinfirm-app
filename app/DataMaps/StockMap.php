<?php

namespace App\DataMaps;


use App\DataMapAccessors\WithCityMap;
use App\DataMapAccessors\WithProductMap;
use App\DataTransferObjects\StockDto;
use App\Models\PrimaryDatabase\Stock;

/**
 * Class StockMap
 */
class StockMap
{
    use WithProductMap;
    use WithCityMap;

    /**
     * @param Stock|null $stock
     * @param StockDto $stockDto
     * @return bool
     */
    public function toStockDto(?Stock $stock, StockDto $stockDto): bool
    {
        try {

            $stockDto
                ->setId($stock[Stock::ID])
                ->setCityId($stock[Stock::CITY_ID])
                ->setProductId($stock[Stock::PRODUCT_ID])
                ->setQuantity($stock[Stock::QUANTITY])
            ;

        } catch (\Exception $exception) {
            return false;
        }

        if ($stock->relationLoaded('product') && $stock->product) {
            $productDto = $stockDto->getProductDto() ?? new \App\DataTransferObjects\ProductDto();
            if ($this->getProductMap()->toProductDto($stock->product, $productDto)) {
                $stockDto->setProductDto($productDto);
            } else {
                return false;
            }
        }

        if ($stock->relationLoaded('city') && $stock->city) {
            $cityDto = $stockDto->getCityDto() ?? new \App\DataTransferObjects\CityDto();
            if ($this->getCityMap()->toCityDto($stock->city, $cityDto)) {
                $stockDto->setCityDto($cityDto);
            } else {
                return false;
            }
        }

        return true;
    }

    public function toStockDtos(array $stocks, array &$stockDtos): bool
    {
        foreach ($stocks as $stock) {
            $stockDto = new StockDto();
            $this->toStockDto($stock, $stockDto);
            $stockDtos[] = $stockDto;
        }
        return true;
    }

    /**
     * @param StockDto $stockDto
     * @param Stock $stock
     * @return bool
     */
    public function fromStockDto(StockDto $stockDto, Stock $stock): bool
    {
        try {

            $stock[Stock::ID] = $stockDto->getId();
            $stock[Stock::CITY_ID] = $stockDto->getCityId();
            $stock[Stock::PRODUCT_ID] = $stockDto->getProductId();
            $stock[Stock::QUANTITY] = $stockDto->getQuantity();

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }
}
