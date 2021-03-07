<?php

namespace App\Models;

use App\Core\Base;

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
class StockTransfer extends Base
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
    protected $fillable = ['transfer_by', 'received_by', 'store_in_id', 'store_out_id', 'request_id', 'transfer_date', 'received_date', 'created_by', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function storeOut()
    {
        return $this->belongsTo(Store::class, 'store_in_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function storeIn()
    {
        return $this->belongsTo(Store::class, 'store_out_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transferBy()
    {
        return $this->belongsTo('App\User', 'transfer_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receivedBy()
    {
        return $this->belongsTo('App\User', 'received_by');
    }

    public function products()
    {
        return $this->belongsToMany(StockTransferProduct::class);
    }
}
