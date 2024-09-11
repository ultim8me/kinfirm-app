<?php

namespace App\DataTransferObjects;

/**
 * Class StockDto
 * @package App\DataTransferObjects
 */
class StockDto extends AbstractDto
{
    use WithId;
    use WithTimestamps;
    use WithCity;
    use WithProduct;

    protected ?int $product_id;
    protected ?int $city_id;
    protected ?int $quantity;

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(?int $product_id): StockDto
    {
        $this->product_id = $product_id;
        return $this;
    }

    public function getCityId(): ?int
    {
        return $this->city_id;
    }

    public function setCityId(?int $city_id): StockDto
    {
        $this->city_id = $city_id;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): StockDto
    {
        $this->quantity = $quantity;
        return $this;
    }

}
