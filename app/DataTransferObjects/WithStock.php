<?php

namespace App\DataTransferObjects;

trait WithStock
{
    /** @var StockDto[]|null $stockDtos */
    protected ?array $stockDtos = [];

    public function getStockDtos(): ?array
    {
        return $this->stockDtos;
    }

    public function setStockDtos(?array $stockDtos): static
    {
        $this->stockDtos = $stockDtos;
        return $this;
    }

    public function getStockIds(): ?array
    {
        $stockIds = [];
        foreach ($this->getStockDtos() ?? [] as $stockDto) {
            $stockIds[] = $stockDto->getId();
        }

        return $stockIds;
    }

}
