<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\ProductDto;
use App\Models\PrimaryDatabase\Product;
use App\Patterns\Controllers\GetRequestProcessor;
use App\ServiceAccessors\WithProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductController extends Controller
{
    use WithProductService;

    /**
     * @return JsonResponse|ResourceCollection
     */
    public function index(): JsonResponse|ResourceCollection
    {
        $processDelegate = fn(&$products) => $this->getProductService()->fetchAll(
            $products
        );

        return (new GetRequestProcessor($processDelegate))
            ->setWithQueryLogging(true)
            ->process();
    }

    /**
     * @param Product $product
     * @return JsonResponse|JsonResource
     */
    public function show(Product $product): JsonResponse|JsonResource
    {
        $productDto = (new ProductDto())->setSku($product->sku);

        $processDelegate = fn(&$response) => $this->getProductService()->fetchBySku(
            $productDto,
            $response
        );

        return (new GetRequestProcessor($processDelegate))
            ->setWithQueryLogging(true)
            ->process();
    }
}
