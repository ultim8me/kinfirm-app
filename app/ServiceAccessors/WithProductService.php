<?php
namespace App\ServiceAccessors;

use App\Services\ProductService;

trait WithProductService
{
    /**
     * @internal This value is managed by getProductService.
     * Direct access is discouraged.
     *
     * @var ?ProductService An ProductService object.
     */
    private ?ProductService $productService = null;

    /**
     * Accessor method to get the ProductService value.
     *
     * @return ProductService An ProductService object.
     */
    public function getProductService() : ProductService
    {
        return $this->productService ?? app(ProductService::class);
    }
}
