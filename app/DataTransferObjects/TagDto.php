<?php

namespace App\DataTransferObjects;

use App\Patterns\Resolver\IResolvableKey;

/**
 * Class TagDto
 * @package App\DataTransferObjects
 */
class TagDto extends AbstractDto implements IResolvableKey
{
    use WithId;
    use WithTimestamps;
    use WithProducts;

    protected ?string $title;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): TagDto
    {
        $this->title = $title;
        return $this;
    }

    public function getCacheKey(): ?string
    {
        if (is_null($this->getId())) {
            return null;
        }
        return sprintf("%s_%d", 'tag', $this->getId());
    }
}
