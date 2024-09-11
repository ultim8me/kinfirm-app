<?php

namespace App\DataTransferObjects;

trait WithProducts
{
    /** @var ProductDto[]|null $productDtos */
    protected ?array $productDtos = [];

    public function getProductDtos(): ?array
    {
        return $this->productDtos;
    }

    public function setProductDtos(?array $productDtos): static
    {
        $this->productDtos = $productDtos;
        return $this;
    }

    public function getProductIds(): ?array
    {
        $productIds = [];
        foreach ($this->getProductDtos() ?? [] as $productDto) {
            $productIds[] = $productDto->getId();
        }

        return $productIds;
    }

}
