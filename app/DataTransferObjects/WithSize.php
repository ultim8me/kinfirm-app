<?php

namespace App\DataTransferObjects;

trait WithSize
{
    protected ?SizeDto $sizeDto  = null;

    public function getSizeDto(): ?SizeDto
    {
        return $this->sizeDto;
    }

    public function setSizeDto(?SizeDto $sizeDto): static
    {
        $this->sizeDto = $sizeDto;

        if (method_exists($this,'setSizeId')) {
            $this->setSizeId($sizeDto->getId());
        }

        return $this;
    }

}
