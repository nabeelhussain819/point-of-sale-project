<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $refund_id
 * @property integer $order_id
 * @property integer $product_id
 * @property string $serial_no
 * @property float $return_cost
 * @property float $quantity
 * @property string $created_at
 * @property string $updated_at
 * @property Refund $refund
 * @property Order $order
 * @property Product $product
 */
class RefundsProduct extends Model
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
    protected $fillable = ['refund_id', 'order_id', 'product_id', 'serial_no', 'return_cost', 'quantity', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function refund()
    {
        return $this->belongsTo('App\Models\Refund');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
