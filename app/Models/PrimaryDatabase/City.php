<?php

namespace App\Models\PrimaryDatabase;

use App\Constants\DatabaseConnections;
use App\Patterns\Search\SearchResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

/**
 * Class City
 *
 * @property int $id
 * @property string $name
 * @property Collection<Product> $products
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\PrimaryDatabase
 */
class City extends Model
{
    use Searchable;

    protected $connection = DatabaseConnections::PRIMARY_DATABASE_CONNECTION_NAME;

    const ID = 'id';
    const NAME = 'name';

    public function searchableAs(): string
    {
        return 'cities_index';
    }

    public function toSearchableArray(): array
    {
        return $this->only( self::ID, self::NAME, self::CREATED_AT);
    }

    public function getSearchResult(bool $onlyPrimaryKey) : SearchResult
    {
        if ($onlyPrimaryKey) {
            return new SearchResult($this->id);
        }

        return new SearchResult(
            $this->id,
            Str::limit(sprintf("%s", $this->name), 40),
            sprintf('/cities/%s', $this->id),
            'city'
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, Stock::class, 'city_id', 'product_id')
            ->as('stock')
            ->withPivot(Stock::QUANTITY)
            ->withTimestamps();
    }
}
