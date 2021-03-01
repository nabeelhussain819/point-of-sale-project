<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $store_in
 * @property integer $store_out
 * @property integer $inventory_id
 * @property string $request
 * @property string $reference
 * @property integer $quantity
 * @property string $date
 * @property string $created_at
 * @property string $updated_at
 * @property Inventory $inventory
 * @property Store $store
 * @property Store $store2
 */
class StockTransfer extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['store_in', 'store_out', 'inventory_id', 'request', 'reference', 'quantity', 'date', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function storeIn()
    {
        return $this->belongsTo(Store::class, 'store_in');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function storeOut()
    {
        return $this->belongsTo(Store::class, 'store_out');
    }
}
