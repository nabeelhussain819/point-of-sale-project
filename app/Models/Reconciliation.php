<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property boolean $is_reconciled
 * @property string $created_at
 * @property string $updated_at
 * @property Store $store
 * @relation  ReconciliationProduct[] $reconciliationProducts
 */
class Reconciliation extends Model
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
    protected $fillable = ['store_id', 'name', 'is_reconciled', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public function reconciliationProducts()
    {
        return $this->belongsToMany(ReconciliationProduct::class, 'reconciliation_products', 'reconciliation_id', 'reconciliation_id');
    }

//    public function purchaseOrdersProducts()
//    {
//        return $this->belongsToMany(ReconciliationProduct::class, 'reconciliation_orders_products', 'reconciliation__id', 'reconciliation__id');
//    }
}
