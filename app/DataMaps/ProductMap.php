<?php

namespace App\DataMaps;



use App\DataMapAccessors\WithSizeMap;
use App\DataMapAccessors\WithTagMap;
use App\DataTransferObjects\ProductDto;
use App\Models\PrimaryDatabase\Product;

/**
 * Class ProductMap
 */
class ProductMap
{

    use WithSizeMap;
    use WithTagMap;

    /**
     * @param Product|null $product
     * @param ProductDto $productDto
     * @return bool
     */
    public function toProductDto(?Product $product, ProductDto $productDto): bool
    {
        try {

            $productDto
                ->setId($product[Product::ID])
                ->setSizeId($product[Product::SIZE_ID])
                ->setSku($product[Product::SKU])
                ->setPhotoUrl($product[Product::PHOTO_URL])
                ->setDescription($product[Product::DESCRIPTION])
                ->setUpdatedAt($product[Product::UPDATED_AT])
            ;

        } catch (\Exception $exception) {
            return false;
        }

        if ($product->relationLoaded('size') && $product->size) {
            $sizeDto = $productDto->getSizeDto() ?? new \App\DataTransferObjects\SizeDto();
            if ($this->getSizeMap()->toSizeDto($product->size, $sizeDto)) {
                $productDto->setSizeDto($sizeDto);
            } else {
                return false;
            }
        }

        if ($product->relationLoaded('tags') && $product->tags) {
            $tagDtos = $productDto->getTagDtos();

            foreach ($product->tags as $tag) {
                if (!array_key_exists($tag->id, $tagDtos)) {
                    $tagDto = new \App\DataTransferObjects\TagDto();
                    if ($this->getTagMap()->toTagDto($tag, $tagDto)) {
                        $tagDtos[$tag->id] = $tagDto;
                    } else {
                        return false;
                    }
                }
            }
            $productDto->setTagDtos($tagDtos);
        }

        if ($product->relationLoaded('stock') && $product->stock) {
            $stockDtos = $productDto->getStockDtos();

            foreach ($product->stocks as $stock) {
                if (!array_key_exists($stock->id, $stockDtos)) {
                    $stockDto = new \App\DataTransferObjects\StockDto();
                    if ($this->getStockMap()->toStockDto($stock, $stockDto)) {
                        $stockDtos[$stock->id] = $stockDto;
                    } else {
                        return false;
                    }
                }
            }
            $productDto->setStockDtos($stockDtos);
        }

        return true;
    }

    public function toProductDtos(array $products, array &$productDtos): bool
    {
        foreach ($products as $product) {
            $productDto = new ProductDto();
            $this->toProductDto($product, $productDto);
            $productDtos[] = $productDto;
        }
        return true;
    }

    /**
     * @param ProductDto $productDto
     * @param Product $product
     * @return bool
     */
    public function fromProductDto(ProductDto $productDto, Product $product): bool
    {
        try {

            $product[Product::ID] = $productDto->getId();
            $product[Product::SIZE_ID] = $productDto->getSizeId();
            $product[Product::SKU] = $productDto->getSku();
            $product[Product::PHOTO_URL] = $productDto->getPhotoUrl();
            $product[Product::DESCRIPTION] = $productDto->getDescription();

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }
}
