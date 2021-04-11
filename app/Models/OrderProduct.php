<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $customer_id
 * @property integer $inventory_id
 * @property integer $store_id
 * @property integer $stock_id
 * @property integer $quantity
 * @property string $order_id
 * @property string $created_at
 * @property string $updated_at
 * @property Customer $customer
 * @property Inventory $inventory
 * @property StockBin $stock
 * @property Store $store
 */
class OrderProduct extends Model
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
    protected $fillable = ['customer_id', 'lookup', 'inventory_id', 'store_id', 'stock_id',
        'retail_price', 'serial_number', 'total', 'min_price',
        'quantity', 'order_id', 'type_id', 'vendor_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

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
    public function stock()
    {
        return $this->belongsTo(StockBin::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);

    }
}
