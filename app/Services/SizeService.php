<?php

namespace App\Services;

use App\Constants\ErrorMessages;
use App\DataAccess\CacheDatabase\ResolvableKeyCache;
use App\DataMapAccessors\WithSizeMap;
use App\DataTransferObjects\SizeDto;
use App\Exceptions\ServiceException;
use App\Http\Resources\SizeResource;
use App\Models\PrimaryDatabase\Size;
use App\Patterns\Log\LogException;
use App\Patterns\Log\LogMessage;
use App\Patterns\MethodResponses\CreateMethodResponse;
use App\Patterns\MethodResponses\FetchMethodResponse;
use App\Patterns\Resolver\KeyResolver;
use App\Patterns\Resolver\WithKeyResolver;
use App\ServiceAccessors\WithCacheService;

class SizeService
{
    use WithSizeMap;
    use WithCacheService;
    use WithKeyResolver;

    /**
     * @param SizeDto $sizeDto
     * @return CreateMethodResponse
     */
    public function createSize(SizeDto $sizeDto): CreateMethodResponse
    {
        try {

            $size = new Size();
            if (!$this->getSizeMap()->fromSizeDto($sizeDto, $size)) {
                throw new ServiceException(ErrorMessages::SIZE_MAP_FROM_DTO_FAILED);
            }

            if (!$size->save()) {
                throw new ServiceException();
            }

            $sizeDto->setId($size->id);

            $methodResponse = $this->getCacheService()->create(
                $sizeDto->getCacheKey(),
                $size
            );
            if ($methodResponse->isFailure()) {
                throw new ServiceException();
            }

        } catch (\Exception|ServiceException $exception) {

            return CreateMethodResponse::error([
                new LogMessage(ErrorMessages::SIZE_CREATE_FAILED),
                new LogException($exception)
            ]);
        }

        return CreateMethodResponse::created();
    }

    /**
     * @param SizeDto $sizeDto
     * @return FetchMethodResponse
     */
    public function fetchByName(SizeDto $sizeDto): FetchMethodResponse
    {
        try {

            $this->getKeyResolver()->resolveId($sizeDto);
            $methodResponse = $this->getCacheService()->fetchValueByKey(
                $sizeDto->getCacheKey(),
                $size
            );

            if ($methodResponse->isNotFound()) {
                $size = Size::where(Size::NAME, $sizeDto->getName())->first();
            }

            if (!$size) {
                return FetchMethodResponse::notFound();
            }

            if (!$this->getSizeMap()->toSizeDto($size, $sizeDto)) {
                throw new ServiceException(ErrorMessages::SIZE_MAP_TO_DTO_FAILED);
            }

        } catch (\Exception|ServiceException $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::SIZE_FETCH_BY_NAME_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param $sizes
     * @return FetchMethodResponse
     */
    public function fetchAll(&$sizes): FetchMethodResponse
    {
        try {

            $sizes = SizeResource::collection((new Size)->paginate());

            if ($sizes->isEmpty()) {
                return FetchMethodResponse::notFound();
            }

        } catch (\Exception $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::SIZES_FETCH_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @return KeyResolver
     */
    private function initializeKeyResolver(): KeyResolver
    {
        return new KeyResolver(
            [Size::NAME],
            new ResolvableKeyCache('size'),
            new Size
        );
    }
}
