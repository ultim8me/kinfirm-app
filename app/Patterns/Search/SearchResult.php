<?php

namespace App\Patterns\Search;

/**
 * Class SearchResult
 */
class SearchResult
{
    public function __construct(
        public int|string $primaryKey,
        public ?string $title = null,
        public ?string $url = null,
        public ?string $type = null,
    ){}
}
