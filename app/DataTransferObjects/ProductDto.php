<?php

namespace App\DataTransferObjects;


use App\Patterns\Resolver\IResolvableKey;

class ProductDto extends AbstractDto implements IResolvableKey
{
    use WithId;
    use WithSku;
    use WithTags;
    use WithStock;
    use WithSize;
    use WithDescription;
    use WithTimestamps;

    protected ?int $size_id;
    protected ?string $photo_url;

    public function getSizeId(): ?int
    {
        return $this->size_id;
    }

    public function setSizeId(?int $size_id): ProductDto
    {
        $this->size_id = $size_id;
        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photo_url;
    }

    public function setPhotoUrl(?string $photo_url): ProductDto
    {
        $this->photo_url = $photo_url;
        return $this;
    }

    public function getCacheKey(): ?string
    {
        if (is_null($this->getId())) {
            return null;
        }
        return sprintf("%s_%d", 'product', $this->getId());
    }
}
