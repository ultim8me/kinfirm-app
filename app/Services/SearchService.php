<?php

namespace App\Services;

use App\Constants\ErrorMessages;
use App\Models\PrimaryDatabase\City;
use App\Models\PrimaryDatabase\Product;
use App\Models\PrimaryDatabase\Tag;
use App\Patterns\Log\LogException;
use App\Patterns\Log\LogMessage;
use App\Patterns\MethodResponses\FetchMethodResponse;
use App\Patterns\Search\SearchResultCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SearchService
 * @package App\Services
 */
class SearchService
{

    /**
     * @param string|null $query
     * @param int $take
     * @param array $response
     * @return FetchMethodResponse
     */
    public function globalSearch(?string $query, int $take, array &$response): FetchMethodResponse
    {
        try {

            $searchResults = new SearchResultCollection();

            $searchResults
                ->addResults($this->queryIndex(new Product(), $query, $take))
                ->addResults($this->queryIndex(new City(), $query, $take))
                ->addResults($this->queryIndex(new Tag(), $query, $take))
            ;

            $response = $searchResults->response('type');

        } catch (\Exception $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::GLOBAL_SEARCH_FAILED),
                (new LogException($exception))->addContext('trace', $exception->getTraceAsString())
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param string|null $query
     * @param int $take
     * @param array $response
     * @return FetchMethodResponse
     */
    public function productSearch(?string $query, int $take, array &$response): FetchMethodResponse
    {
        try {

            $searchResults = new SearchResultCollection();

            $searchResults->addResults($this->queryIndex(new Product(), $query, $take), false);

            $response = $searchResults->response();

        } catch (\Exception $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::PRODUCT_SEARCH_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param Model $model
     * @param string|null $term
     * @param int $take
     * @return Collection
     */
    private function queryIndex(
        Model $model,
        ?string $term,
        int $take,
    ): Collection {

        if (is_null($term)) {
            return Collection::empty();
        }

        $query = $model->search($term)->take($take)->orderBy('created_at', 'desc');

        return $query->get();
    }
}


