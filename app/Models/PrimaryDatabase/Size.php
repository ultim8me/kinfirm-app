<?php

namespace App\Models\PrimaryDatabase;

use App\Constants\DatabaseConnections;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Size
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property Collection<Product> $products
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\PrimaryDatabase
 */
class Size extends Model
{
    protected $connection = DatabaseConnections::PRIMARY_DATABASE_CONNECTION_NAME;

    const ID = 'id';
    const NAME = 'name';
    const DESCRIPTION = 'description';

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
