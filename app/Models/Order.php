<?php

namespace App\Models;

use App\Core\Base;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $customer_id
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property Customer $customer
 * @property Store store_id
 * @property Product $product
 */
class Order extends Base
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
    protected $fillable = ['customer_id', 'discount', 'without_tax', 'sub_total',
        'cash_paid', 'cash_back', 'customer_card_number',
        'tax', 'finance_id', 'store_id',
        'without_discount', 'created_at', 'updated_at'];
    /**
     * @var array
     */
    protected $appends = ["date"];

    public $POSTEDPRODUCTS;

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

    public function withProductsProduct()
    {
        $this->load(['products' => function ($query) {
            $query->with(['product' => function ($query) {
                $query->select(['id', 'name']);
            }]);
        }]);
        return $this;
    }

    public function finance()
    {
        $this->belongsTo(Finance::class);
    }

    public static function financeCreate(Finance $finance)
    {
        $order = new Order();
        $order->fill([
            'customer_id' => $finance->customer_id,
            'sub_total' => $finance->total,
            'finance_id' => $finance->id
        ]);
        $order->POSTEDPRODUCTS = [
            [
                'product_id' => $finance->product_id,
                'quantity' => 1,
                'serial_number' => $finance->serial_number,
                'store_id' => $finance->store_id
            ]
        ];
        $order->save();
        $order->ordersProducts()->sync($order->POSTEDPRODUCTS);
        return $order;
    }

    public static function boot()
    {
        parent::boot();

        Order::observe(OrderObserver::class);
    }
}
