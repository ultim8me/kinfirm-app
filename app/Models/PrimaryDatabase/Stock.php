<?php

namespace App\Models\PrimaryDatabase;

use App\Constants\DatabaseConnections;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class Stock
 *
 * @property int $id
 * @property integer product_id
 * @property integer city_id
 * @property integer $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\PrimaryDatabase
 */
class Stock extends Pivot
{
    protected $connection = DatabaseConnections::PRIMARY_DATABASE_CONNECTION_NAME;
    protected $table = 'product_stock';
    public $incrementing = true;

    const ID = 'id';
    const PRODUCT_ID = 'product_id';
    const CITY_ID = 'city_id';
    const QUANTITY = 'quantity';

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function city(): BelongsToMany
    {
        return $this->belongsToMany(City::class);
    }
}
