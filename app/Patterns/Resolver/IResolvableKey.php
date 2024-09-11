<?php

namespace App\Patterns\Resolver;

/**
 * Interface IResolvableKey
 *
 * @package App\Patterns\Resolver
 */
interface IResolvableKey
{
    public function getId(): ?int;
    public function setId(?int $id): static;
    public function getCacheKey(): ?string;
}
