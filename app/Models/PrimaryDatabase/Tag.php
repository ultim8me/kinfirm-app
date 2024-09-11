<?php

namespace App\Models\PrimaryDatabase;

use App\Constants\DatabaseConnections;
use App\Patterns\Search\SearchResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

/**
 * Class Tag
 *
 * @property int $id
 * @property string $title
 * @property Collection<Product> $products
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\PrimaryDatabase
 */
class Tag extends Model
{
    use HasFactory;
    use Searchable;

    protected $connection = DatabaseConnections::PRIMARY_DATABASE_CONNECTION_NAME;

    const ID = 'id';
    const TITLE = 'title';

    public function searchableAs(): string
    {
        return 'tags_index';
    }

    public function toSearchableArray(): array
    {
        return $this->only( self::ID, self::TITLE, self::CREATED_AT);
    }

    public function getSearchResult(bool $onlyPrimaryKey) : SearchResult
    {
        if ($onlyPrimaryKey) {
            return new SearchResult($this->id);
        }

        return new SearchResult(
            $this->id,
            Str::limit(sprintf("%s", $this->title), 40),
            sprintf('/tags/%s', $this->id),
            'tag'
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

}
