<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property OrderProduct[] $orderProducts
 */
class StockBin extends Model
{

    const RETAIL = 1;
    const TYPE_RETURN = 2;
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     * @!!! @constant is language variable will cause an issue
     */
    protected $fillable = ['name', 'constant', 'created_at', 'updated_at', 'system'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

}
