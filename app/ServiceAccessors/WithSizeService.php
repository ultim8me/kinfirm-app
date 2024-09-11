<?php
namespace App\ServiceAccessors;

use App\Services\SizeService;

trait WithSizeService
{
    /**
     * @internal This value is managed by getSizeService.
     * Direct access is discouraged.
     *
     * @var ?SizeService An SizeService object.
     */
    private ?SizeService $sizeService = null;

    /**
     * Accessor method to get the SizeService value.
     *
     * @return SizeService An SizeService object.
     */
    public function getSizeService() : SizeService
    {
        return $this->sizeService ?? app(SizeService::class);
    }
}
