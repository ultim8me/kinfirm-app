<?php

namespace App\DataTransferObjects;

use App\Patterns\Resolver\IResolvableKey;

/**
 * Class SizeDto
 * @package App\DataTransferObjects
 */
class SizeDto extends AbstractDto implements IResolvableKey
{
    use WithId;
    use WithDescription;
    use WithTimestamps;
    use WithProducts;

    protected ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): SizeDto
    {
        $this->name = $name;
        return $this;
    }

    public function getCacheKey(): ?string
    {
        if (is_null($this->getId())) {
            return null;
        }
        return sprintf("%s_%d", 'size', $this->getId());
    }
}
