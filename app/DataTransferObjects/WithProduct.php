<?php

namespace App\DataTransferObjects;

trait WithProduct
{
    protected ?ProductDto $productDto  = null;

    public function getProductDto(): ?ProductDto
    {
        return $this->productDto;
    }

    public function setProductDto(?ProductDto $productDto): static
    {
        $this->productDto = $productDto;

        if (method_exists($this,'setProductId')) {
            $this->setProductId($productDto->getId());
        }

        return $this;
    }

}
