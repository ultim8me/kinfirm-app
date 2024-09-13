<?php

namespace App\Services;

use App\Constants\ErrorMessages;
use App\DataAccess\CacheDatabase\ResolvableKeyCache;
use App\DataMapAccessors\WithProductMap;
use App\DataTransferObjects\ProductDto;
use App\DataTransferObjects\StockDto;
use App\Exceptions\ServiceException;
use App\Http\Resources\ProductResource;
use App\Models\PrimaryDatabase\Product;
use App\Models\PrimaryDatabase\Stock;
use App\Patterns\Log\LogException;
use App\Patterns\Log\LogMessage;
use App\Patterns\MethodResponses\CreateMethodResponse;
use App\Patterns\MethodResponses\FetchMethodResponse;
use App\Patterns\Resolver\KeyResolver;
use App\Patterns\Resolver\WithKeyResolver;
use App\ServiceAccessors\WithCacheService;

class ProductService
{
    use WithProductMap;
    use WithCacheService;
    use WithKeyResolver;

    /**
     * @param ProductDto $productDto
     * @return CreateMethodResponse
     */
    public function createProduct(ProductDto $productDto): CreateMethodResponse
    {
        /*
         * TODO add DTO validation if method will be used from other services
         */

        try {

            $product = new Product();
            if (!$this->getProductMap()->fromProductDto($productDto, $product)) {
                throw new ServiceException(ErrorMessages::PRODUCT_MAP_FROM_DTO_FAILED);
            }

            if (!$product->save()) {
                throw new ServiceException();
            }

            if (!empty($productDto->getTagDtos())) {
                $product->tags()->attach($productDto->getTagIds());
            }

            $productDto->setId($product->id);

            $methodResponse = $this->getCacheService()->create(
                $productDto->getCacheKey(),
                $product->load(['tags', 'size'])
            );
            if ($methodResponse->isFailure()) {
                throw new ServiceException();
            }

        } catch (\Exception|ServiceException $exception) {
            return CreateMethodResponse::error([
                new LogMessage(ErrorMessages::PRODUCT_CREATE_FAILED),
                new LogException($exception)
            ]);
        }

        return CreateMethodResponse::created();
    }

    /**
     * @param $products
     * @return FetchMethodResponse
     */
    public function fetchAll(&$products): FetchMethodResponse
    {
        try {

            $products = ProductResource::collection((new Product)->withOnly(['size', 'tags'])->paginate(12));

            if ($products->isEmpty()) {
                return FetchMethodResponse::notFound();
            }

        } catch (\Exception $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::PRODUCTS_FETCH_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param ProductDto $productDto
     * @param Product|null $response
     * @return FetchMethodResponse
     */
    public function fetchBySku(ProductDto $productDto, mixed &$response = null): FetchMethodResponse
    {
        try {

            $this->getKeyResolver()->resolveId($productDto);
            $methodResponse = $this->getCacheService()->fetchValueByKey(
                $productDto->getCacheKey(),
                $product
            );

            if ($methodResponse->isNotFound()) {
                $product = Product::where(Product::SKU, $productDto->getSku())->first();;
            }

            if (!$product) {
                return FetchMethodResponse::notFound();
            }

            if (!$this->getProductMap()->toProductDto($product, $productDto)) {
                throw new ServiceException(ErrorMessages::PRODUCT_MAP_TO_DTO_FAILED);
            }

            $response = $product->load(['cities']);

        } catch (\Exception|ServiceException $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::PRODUCT_FETCH_BY_SKU_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param ProductDto $productDto
     * @param StockDto $stockDto
     * @return CreateMethodResponse
     */
    public function addProductStock(ProductDto $productDto, StockDto $stockDto): CreateMethodResponse
    {
        try {

            $product = new Product();
            if (!$this->getProductMap()->fromProductDto($productDto, $product)) {
                throw new ServiceException(ErrorMessages::PRODUCT_MAP_FROM_DTO_FAILED);
            }

            $product->cities()->attach($stockDto->getCityId(), [Stock::QUANTITY => $stockDto->getQuantity()]);

        } catch (\Exception|ServiceException $exception) {

            return CreateMethodResponse::error([
                new LogMessage(ErrorMessages::ADD_PRODUCT_STOCK_FAILED),
                new LogException($exception)
            ]);
        }

        return CreateMethodResponse::created();
    }

    /**
     * @return KeyResolver
     */
    private function initializeKeyResolver(): KeyResolver
    {
        return new KeyResolver(
            [Product::SKU],
            new ResolvableKeyCache('product'),
            new Product
        );
    }
}
