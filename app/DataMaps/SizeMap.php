<?php

namespace App\DataMaps;


use App\DataMapAccessors\WithProductMap;
use App\DataTransferObjects\SizeDto;
use App\Models\PrimaryDatabase\Size;

/**
 * Class SizeMap
 */
class SizeMap
{
    use WithProductMap;

    /**
     * @param Size|null $size
     * @param SizeDto $sizeDto
     * @return bool
     */
    public function toSizeDto(?Size $size, SizeDto $sizeDto): bool
    {
        try {

            $sizeDto
                ->setId($size[Size::ID])
                ->setName($size[Size::NAME])
                ->setDescription($size[Size::DESCRIPTION])
            ;

        } catch (\Exception $exception) {
            return false;
        }

        if ($size->relationLoaded('products') && $size->products) {
            $productDtos = $sizeDto->getProductDtos();

            foreach ($size->products as $product) {
                if (!array_key_exists($product->id, $productDtos)) {
                    $productDto = new \App\DataTransferObjects\ProductDto();
                    if ($this->getProductMap()->toProductDto($product, $productDto)) {
                        $productDtos[$product->id] = $productDto;
                    } else {
                        return false;
                    }
                }
            }
            $sizeDto->setProductDtos($productDtos);
        }

        return true;
    }

    public function toSizeDtos(array $sizes, array &$sizeDtos): bool
    {
        foreach ($sizes as $size) {
            $sizeDto = new SizeDto();
            $this->toSizeDto($size, $sizeDto);
            $sizeDtos[] = $sizeDto;
        }
        return true;
    }

    /**
     * @param SizeDto $sizeDto
     * @param Size $size
     * @return bool
     */
    public function fromSizeDto(SizeDto $sizeDto, Size $size): bool
    {
        try {

            $size[Size::ID] = $sizeDto->getId();
            $size[Size::NAME] = $sizeDto->getName();
            $size[Size::DESCRIPTION] = $sizeDto->getDescription();

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }
}
