<?php

namespace App\DataMapAccessors;

use App\DataMaps\UserMap;

/**
 * Trait WithUserMap
 */
trait WithUserMap
{
    private ?UserMap $userMap = null;

    public function getUserMap(): ?UserMap
    {
        return $this->userMap ?? app(UserMap::class);
    }
}
