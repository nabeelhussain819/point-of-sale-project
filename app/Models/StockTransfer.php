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

    protected $dates = ['transfer_date'];
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

    //hot fix have to find the COC issue not mapping correctly
    public function transferProducts()
    {
        return $this->belongsToMany(StockTransferProduct::class, 'stock_transfer_products', 'stock_transfer_id', 'stock_transfer_id');
    }

    public function products()
    {
        return $this->hasMany(StockTransferProduct::class);
    }

    public function withProducts()
    {
        $this->load(['products']);
        return $this;
    }
}
