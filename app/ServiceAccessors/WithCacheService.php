<?php
namespace App\ServiceAccessors;

use App\Services\CacheService;

trait WithCacheService
{
    /**
     * @internal This value is managed by getCacheService.
     * Direct access is discouraged.
     *
     * @var ?CacheService An CacheService object.
     */
    private ?CacheService $cacheService = null;

    /**
     * Accessor method to get the CacheService value.
     *
     * @return CacheService An CacheService object.
     */
    public function getCacheService() : CacheService
    {
        return $this->cacheService ?? app(CacheService::class);
    }
}
