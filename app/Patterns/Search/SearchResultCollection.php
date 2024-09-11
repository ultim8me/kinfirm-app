<?php

namespace App\Patterns\Search;

use Illuminate\Support\Collection;

class SearchResultCollection extends Collection
{
    public function addResults(Collection $results, bool $onlyPrimaryKey = false): static
    {
        try {
            $results->each(function ($result) use($onlyPrimaryKey) {
                $this->items[] = $result->getSearchResult($onlyPrimaryKey);
            });
        } catch (\Exception $exception) {

        }

        return $this;
    }

    public function response(?string $groupBy = null): array
    {
        return ['data' => $groupBy ? $this->groupBy($groupBy) : $this->all(), 'meta' => [ 'total' => $this->count() ]];
    }

}
