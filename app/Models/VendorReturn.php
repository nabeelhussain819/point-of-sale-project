<?php

namespace App\Models;

use App\Core\Base;
use App\Observers\InventoryObserver;
use App\Scopes\StockBinGlobalScope;
use App\Scopes\StoreGlobalScope;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $vendor_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $store_id
 * @property string $guid
 * @property integer $quantity
 * @property boolean $has_serial_number
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property User $create
 * @property Product $product
 * @property Vendor $vendor
 * @property Store $store
 */
class VendorReturn extends Base
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
    protected $fillable = ['product_id', 'vendor_id', 'created_by', 'updated_by', 'store_id', 'guid', 'quantity', 'has_serial_number', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

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
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreGlobalScope);

    }

    public static function editOrInsert($data)
    {
        $vendorReturn = VendorReturn::where('vendor_id', $data['vendor_id'])
            ->where('product_id', $data['product_id'])
            ->where('store_id', $data['store_id'])
            ->first();


        // ager null hai tou insert kardo
        if (empty($vendorReturn)) {
            VendorReturn::create(
                [
                    'vendor_id' => $data['vendor_id'],
                    'product_id' => $data['product_id'],
                    'store_id' => $data['store_id'],
                    'quantity' => $data['quantity'],
                ]
            );
        } else {

            $vendorReturn->update([
                'quantity' => $vendorReturn->quantity + $data['quantity'],
            ]);
        }
    }
}
