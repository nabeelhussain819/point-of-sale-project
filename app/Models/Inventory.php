<?php

namespace App\Models;

use App\Core\Base;
use App\Observers\InventoryObserver;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $vendor_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property Product $product
 * @property Vendor $vendor
 * @property string quantity
 */
class Inventory extends Base
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    protected $autoBlame = false;
    /**
     * @var array
     */
    protected $fillable = ['product_id', 'vendor_id', 'name', 'quantity', 'lookup', 'description', 'UPC', 'cost', 'extended_cost', 'stock_bin_id', 'created_at', 'store_id', 'updated_at', 'has_serial_number'];

    const
        SCENARIO_TRANSFER = 'TRANSFER',
        SCENARIO_PURCHASE = 'PURCHASE';

    public $INCOMING_PRODUCTS = false;
    public $OUTGOING_PRODUCTS = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function sales()
    {
        return $this->hasMany(Order::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public static function boot()
    {
        parent::boot();

        Inventory::observe(InventoryObserver::class);
    }
}
