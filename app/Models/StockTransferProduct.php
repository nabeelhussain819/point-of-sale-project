<?php

namespace App\Models;

use App\Core\Base;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $stock_transfer_id
 * @property float $quantity
 * @property string $created_at
 * @property string $updated_at
 * @property Product $product
 * @property StockTransfer $stockTransfer
 */
class StockTransferProduct extends Base
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
    protected $fillable = ['product_id', 'stock_transfer_id', 'quantity', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stockTransfer()
    {
        return $this->belongsTo('App\Models\StockTransfer');
    }
}
