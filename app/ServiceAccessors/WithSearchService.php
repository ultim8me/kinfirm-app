<?php
namespace App\ServiceAccessors;

use App\Services\SearchService;

trait WithSearchService
{
    /**
     * @internal This value is managed by getSearchService.
     * Direct access is discouraged.
     *
     * @var ?SearchService An SearchService object.
     */
    private ?SearchService $searchService = null;

    /**
     * Accessor method to get the SearchService value.
     *
     * @return SearchService An SearchService object.
     */
    public function getSearchService() : SearchService
    {
        return $this->searchService ?? app(SearchService::class);
    }
}
