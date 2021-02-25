<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $vendor_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property Product $product
 * @property Vendor $vendor
 */
class Inventory extends Model
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
    protected $fillable = ['product_id', 'vendor_id', 'name', 'quantity','lookup','description','UPC','cost','extended_cost','bin','created_at', 'updated_at'];

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
}
