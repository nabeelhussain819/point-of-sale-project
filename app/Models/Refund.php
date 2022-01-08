<?php

namespace App\Models;

use App\Core\Base;
use App\Observers\RefundObserver;
use App\Scopes\StoreGlobalScope;
use App\Traits\InteractWithProducts;

/**
 * @property integer $id
 * @property integer $order_id
 * @property float $return_cost
 * @property float $previous_total
 * @property float $previous_sub_total
 * @property float $previous_discount_amount
 * @property string $created_at
 * @property string $updated_at
 * @property Order $order
 * @property RefundsProduct[] $refundsProducts
 */
class Refund extends Base
{
    use InteractWithProducts;
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';
    protected $hasGuid = false;
    public $PostedProducts;

    /**
     * @var array
     */
    protected $fillable = ['order_id', 'return_cost', 'store_id', 'created_at', 'previous_total', 'previous_sub_total', 'previous_discount_amount', 'updated_at', 'created_by', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }


    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\RefundsProduct');
    }

    public function refundsProducts()
    {
        return $this->belongsToMany(RefundsProduct::class, 'refunds_products', 'refund_id', 'refund_id');
    }

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreGlobalScope);
        Refund::observe(RefundObserver::class);
    }
}
