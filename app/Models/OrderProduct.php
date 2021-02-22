<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $quantity
 * @property integer $id
 * @property integer $customer_id
 * @property integer $inventory_id
 * @property string $order_id
 * @property string $created_at
 * @property string $updated_at
 * @property Customer $customer
 * @property Inventory $inventory
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
    protected $fillable = ['customer_id', 'inventory_id', 'quantity','order_id', 'created_at', 'updated_at'];

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
}
