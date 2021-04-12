<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $customer_id
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property Customer $customer
 * @property Product $product
 */
class Order extends Model
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
    protected $fillable = [ 'customer_id', 'discount', 'without_tax', 'sub_total',
        'cash_paid','cash_back','customer_card_number',
        'tax',
        'without_discount', 'created_at', 'updated_at'];

    protected $appends = ["date"];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ordersProducts()
    {
        return $this->belongsToMany(OrderProduct::class, 'order_products', 'order_id', 'order_id');
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

    public function withCustomer()
    {
        $this->load('customer');
        return $this;
    }

    public function withProducts()
    {
        $this->load('products');
        return $this;
    }
//    public function stock()
//    {
//        return $this->belongsTo(StockBin::class);
//    }
}
