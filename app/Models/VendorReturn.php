<?php

namespace App\Models;

use App\Core\Base;

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
        return $this->belongsTo('App\Product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Store');
    }
}
