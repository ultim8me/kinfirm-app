<?php

namespace App\DataMaps;


use App\DataMapAccessors\WithProductMap;
use App\DataTransferObjects\TagDto;
use App\Models\PrimaryDatabase\Tag;

/**
 * Class TagMap
 */
class TagMap
{
    use WithProductMap;

    /**
     * @param Tag|null $tag
     * @param TagDto $tagDto
     * @return bool
     */
    public function toTagDto(?Tag $tag, TagDto $tagDto): bool
    {
        try {

            $tagDto
                ->setId($tag[Tag::ID])
                ->setTitle($tag[Tag::TITLE])
            ;

        } catch (\Exception $exception) {
            return false;
        }

        if ($tag->relationLoaded('products') && $tag->products) {
            $productDtos = $tagDto->getProductDtos();

            foreach ($tag->products as $product) {
                if (!array_key_exists($product->id, $productDtos)) {
                    $productDto = new \App\DataTransferObjects\ProductDto();
                    if ($this->getProductMap()->toProductDto($product, $productDto)) {
                        $productDtos[$product->id] = $productDto;
                    } else {
                        return false;
                    }
                }
            }
            $tagDto->setProductDtos($productDtos);
        }

        return true;
    }

    public function toTagDtos(array $tags, array &$tagDtos): bool
    {
        foreach ($tags as $tag) {
            $tagDto = new TagDto();
            $this->toTagDto($tag, $tagDto);
            $tagDtos[] = $tagDto;
        }
        return true;
    }

    /**
     * @param TagDto $tagDto
     * @param Tag $tag
     * @return bool
     */
    public function fromTagDto(TagDto $tagDto, Tag $tag): bool
    {
        try {

            $tag[Tag::ID] = $tagDto->getId();
            $tag[Tag::TITLE] = $tagDto->getTitle();

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }
}
