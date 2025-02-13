<?php

namespace App\Models;

use App\Core\Base;
use App\Observers\OrderObserver;
use App\Scopes\StoreGlobalScope;
use App\Traits\InteractWithProducts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    use InteractWithProducts;
    protected $hasGuid = false;
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
        'cash_paid', 'cash_back', 'customer_card_number', 'card_paid',
        'store_id', 'tax', 'finance_id', 'store_id', 'notes',
        'without_discount', 'created_at', 'updated_at'];
    /**
     * @var array
     */
    protected $appends = ["date"];

    public $POSTEDPRODUCTS, $LOGDATA;

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
        if (!empty($this->created_at)) {
            return $this->created_at->format('Y-m-d h:m a');
        }

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

    public function refunds()
    {
        return $this->hasMany(Refund::class);
        // $this->hasMany(Refund::class,'order_id');
    }

    public function withRefund()
    {
        $this->load(['refunds' => function (HasMany $hasMany) {

            $hasMany->with(['products']);
        }]);
        return $this;
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
        $order->LOGDATA = [
            'subject' => Finance::class,
            'subject_id' => $finance->id,
            'subject_data' => $finance,
        ];
        $order->save();
        $order->ordersProducts()->sync($order->POSTEDPRODUCTS);
        return $order;
    }

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreGlobalScope);
        Order::observe(OrderObserver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }
}
