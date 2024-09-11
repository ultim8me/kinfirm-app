<?php

namespace App\Models\PrimaryDatabase;

use App\Constants\DatabaseConnections;
use App\Models\WithSku;
use App\Patterns\Search\SearchResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

/**
 * Class Product
 *
 * @property int $id
 * @property string $sku
 * @property string $photo_url
 * @property string $description
 * @property Size $size
 * @property Collection<Tag> $tags
 * @property Collection<City> $cities
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @package App\Models\PrimaryDatabase
 */
class Product extends Model
{
    use SoftDeletes;
    use Searchable;
    use WithSku;

    protected $connection = DatabaseConnections::PRIMARY_DATABASE_CONNECTION_NAME;

    const ID = 'id';
    const PHOTO_URL = 'photo_url';
    const SIZE_ID = 'size_id';
    const DESCRIPTION = 'description';
    const DELETED_AT = 'deleted_at';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        self::CREATED_AT,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        self::DELETED_AT => 'datetime',
    ];

    public function searchableAs(): string
    {
        return 'products_index';
    }

    public function toSearchableArray(): array
    {
        return $this->only( self::SKU, self::DESCRIPTION, self::CREATED_AT);
    }

    public function getSearchResult(bool $onlyPrimaryKey) : SearchResult
    {
        if ($onlyPrimaryKey) {
            return new SearchResult($this->sku);
        }

        return new SearchResult(
            $this->sku,
            Str::limit(sprintf("%s (%s)", $this->description, $this->size->name), 40),
            sprintf('/products/%s', $this->sku), //for front-end navigation
            'product'
        );
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, Stock::class, 'product_id', 'city_id')
            ->as('stock')
            ->withPivot(Stock::QUANTITY)
            ->withTimestamps();
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

}
