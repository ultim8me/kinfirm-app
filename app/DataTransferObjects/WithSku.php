<?php

namespace App\DataTransferObjects;

trait WithSku
{
    protected ?string $sku = null;

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): static
    {
        $this->sku = $sku;
        return $this;
    }
}
