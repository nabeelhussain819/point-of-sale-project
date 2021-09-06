<?php

namespace App\Models;

use App\Core\Base;
use App\Helpers\DateTimeHelper;
use App\Observers\PurchaseOrderObserver;
use App\Traits\InteractWithProducts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property integer $id
 * @property integer $vendor_id
 * @property integer $store_id
 * @property float $price
 * @property float $expected_price
 * @property float $shiping_cost
 * @property string $expected_date
 * @property string $received_date
 * @property string $created_at
 * @property string $updated_at
 * @property Store $store
 * @property Vendor $vendor
 * @relation PurchaseOrdersProduct[] $purchaseOrdersProducts
 */
class PurchaseOrder extends Base
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
    protected $fillable = ['vendor_id', 'store_id', 'price', 'expected_price', 'shiping_cost', 'expected_date', 'received_date', 'created_at', 'updated_at'];

    protected $dates = ['expected_date', 'received_date',];
    public $isReceiving = false;

    public $appends = ['created_date', 'is_received'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function purchaseOrdersProducts()
    {
        return $this->belongsToMany(PurchaseOrdersProduct::class, 'purchase_orders_products', 'purchase_order_id', 'purchase_order_id');
    }

    public function products()
    {
        return $this->hasMany(PurchaseOrdersProduct::class);
    }

    public static function boot()
    {
        parent::boot();

        PurchaseOrder::observe(PurchaseOrderObserver::class);
    }

    public function productSerialNumbers()
    {
        return $this->belongsToMany(ProductSerialNumbers::class, 'product_serial_numbers', 'purchase_order_id', 'purchase_order_id');
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->format(DateTimeHelper::DATE_FORMAT_DEFAULT);
    }

    public function getIsReceivedAttribute(): bool
    {
        if (empty($this->received_date)) {
            return false;
        }
        return true;
    }
}
