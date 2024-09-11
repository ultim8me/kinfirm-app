<?php

namespace App\Patterns\Resolver;

/**
 * Trait WithKeyResolver
 *
 * @package App\Patterns\Resolver
 */
trait WithKeyResolver
{
    private ?KeyResolver $keyResolver = null;

    public function getKeyResolver(): KeyResolver
    {
        return $this->keyResolver ??= $this->initializeKeyResolver();
    }

    abstract private function initializeKeyResolver(): KeyResolver;
}
