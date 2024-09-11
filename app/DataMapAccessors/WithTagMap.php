<?php

namespace App\DataMapAccessors;

use App\DataMaps\TagMap;

/**
 * Trait WithTagMap
 */
trait WithTagMap
{
    private ?TagMap $tagMap = null;

    public function getTagMap(): ?TagMap
    {
        return $this->tagMap ?? app(TagMap::class);
    }
}
