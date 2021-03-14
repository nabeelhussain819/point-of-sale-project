<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $reconciliation_id
 * @property integer $product_id
 * @property float $physical_quantity
 * @property float $system_quantity
 * @property float $adjust_quantity
 * @property Product $product
 * @property Reconciliation $reconciliation
 */
class ReconciliationProduct extends Model
{

    public $timestamps = false;
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['reconciliation_id', 'product_id', 'physical_quantity', 'system_quantity', 'adjust_quantity'];

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
    public function reconciliation()
    {
        return $this->belongsTo('App\Reconciliation');
    }

    public static function updateSystemQuantityAndAjustQuantityByReconcilationIdAndProductId(
        $reconciliation_id, 
        $product_id, 
        $quantity, 
        $difference 
    ){
        return ReconciliationProduct::where(
            [
                ['reconciliation_id' , '=', $reconciliation_id],
                ['product_id',  '=', $product_id]
            ]
            // 'id', '=', $iConcileId

        )->update(
            array(
                'system_quantity' => $quantity,
                'adjust_quantity' => $difference,
                // 'timestamps' => false
            // ) 
             )
        );
    }
}
