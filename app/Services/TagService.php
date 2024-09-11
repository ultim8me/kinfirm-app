<?php

namespace App\Services;

use App\Constants\ErrorMessages;
use App\DataAccess\CacheDatabase\ResolvableKeyCache;
use App\DataMapAccessors\WithTagMap;
use App\DataTransferObjects\TagDto;
use App\Exceptions\ServiceException;
use App\Http\Resources\TagResource;
use App\Models\PrimaryDatabase\Tag;
use App\Patterns\Log\LogException;
use App\Patterns\Log\LogMessage;
use App\Patterns\MethodResponses\CreateMethodResponse;
use App\Patterns\MethodResponses\FetchMethodResponse;
use App\Patterns\Resolver\KeyResolver;
use App\Patterns\Resolver\WithKeyResolver;
use App\ServiceAccessors\WithCacheService;

class TagService
{
    use WithTagMap;
    use WithCacheService;
    use WithKeyResolver;

    /**
     * @param TagDto $tagDto
     * @return CreateMethodResponse
     */
    public function createTag(TagDto $tagDto): CreateMethodResponse
    {
        try {

            $tag = new Tag();
            if (!$this->getTagMap()->fromTagDto($tagDto, $tag)) {
                throw new ServiceException(ErrorMessages::TAG_MAP_FROM_DTO_FAILED);
            }

            if (!$tag->save()) {
                throw new ServiceException();
            }

            $tagDto->setId($tag->id);

            if (!empty($tagDto->getProductDtos())) {
                $tag->products()->attach($tagDto->getProductIds());
            }

            $methodResponse = $this->getCacheService()->create(
                $tagDto->getCacheKey(),
                $tag
            );
            if ($methodResponse->isFailure()) {
                throw new ServiceException();
            }

        } catch (\Exception|ServiceException $exception) {

            return CreateMethodResponse::error([
                new LogMessage(ErrorMessages::TAG_CREATE_FAILED),
                new LogException($exception)
            ]);
        }

        return CreateMethodResponse::created();
    }

    /**
     * @param $tags
     * @return FetchMethodResponse
     */
    public function fetchAll(&$tags): FetchMethodResponse
    {
        try {

            $tags = TagResource::collection((new Tag)->paginate());

            if ($tags->isEmpty()) {
                return FetchMethodResponse::notFound();
            }

        } catch (\Exception $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::TAGS_FETCH_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param TagDto $tagDto
     * @return FetchMethodResponse
     */
    public function fetchByTitle(TagDto $tagDto): FetchMethodResponse
    {
        try {

            $this->getKeyResolver()->resolveId($tagDto);
            $methodResponse = $this->getCacheService()->fetchValueByKey(
                $tagDto->getCacheKey(),
                $tag
            );

            if ($methodResponse->isNotFound()) {
                $tag = Tag::where(Tag::TITLE, $tagDto->getTitle())->first();
            }

            if (!$tag) {
                return FetchMethodResponse::notFound();
            }

            if (!$this->getTagMap()->toTagDto($tag, $tagDto)) {
                throw new ServiceException(ErrorMessages::TAG_MAP_TO_DTO_FAILED);
            }

        } catch (\Exception|ServiceException $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::TAG_FETCH_BY_TITLE_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param $tags
     * @param int $take
     * @return FetchMethodResponse
     */
    public function fetchPopular(&$tags, int $take = 5): FetchMethodResponse
    {
        try {

            $tagIds = (new Tag)
                ->join('product_tag', 'tags.id', 'product_tag.tag_id')
                ->join('products', 'product_tag.product_id', 'products.id')
//                ->leftJoin('product_stock', 'products.id', 'product_stock.product_id')
                ->selectRaw('count(tags.id) as products_count, tags.id, tags.title')
//                ->selectRaw('sum(quantity) as products_count, tags.id, tags.title')
                ->groupBy('id', 'title')
                ->having('products_count', '>=', 1)
                ->take($take)
                ->get()
                ->sortByDesc('products_count');

            $tags = TagResource::collection($tagIds);

            if ($tags->isEmpty()) {
                return FetchMethodResponse::notFound();
            }

        } catch (\Exception|ServiceException $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::POPULAR_TAGS_FETCH_FAILED),
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
            [Tag::TITLE],
            new ResolvableKeyCache('tag'),
            new Tag
        );
    }
}
