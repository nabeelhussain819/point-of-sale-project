<?php

namespace App\Models;

use App\Core\Base;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $purchase_order_id
 * @property float $price
 * @property float $quantity
 * @property float $expected_price
 * @relation Product $product
 * @relation PurchaseOrder $purchaseOrder
 */
class PurchaseOrdersProduct extends Base
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     *
     * disabled timestamp
     * @var Bool
     */
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['product_id', 'purchase_order_id', 'price', 'expected_price', 'quantity', 'total_price', 'total', 'expected_total'];

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
    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrder');
    }

    public function getSerialNumbers()
    {
        return ProductSerialNumbers::getPurchaseProduct($this->product_id, $this->purchase_order_id)->get();
    }

}
