<?php

namespace App\DataTransferObjects;

use App\Patterns\Resolver\IResolvableKey;

/**
 * Class CityDto
 * @package App\DataTransferObjects
 */
class CityDto extends AbstractDto implements IResolvableKey
{
    use WithId;
    use WithTimestamps;

    protected ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): CityDto
    {
        $this->name = $name;
        return $this;
    }

    public function getCacheKey(): ?string
    {
        if (is_null($this->getId())) {
            return null;
        }
        return sprintf("%s_%d", 'city', $this->getId());
    }
}
