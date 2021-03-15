<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\Base;
/**
 * @property integer $id
 * @property integer $customer_id
 * @property string $status
 * @property float $total_cost
 * @property float $advance_cost
 * @property string $guid
 * @property string $created_at
 * @property string $updated_at
 * @property Customer $customer
 */
class Repair extends Base
{
    protected $autoBlame = false;
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'status', 'total_cost', 'advance_cost', 'guid', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function products()
    {
        return $this->belongsToMany(RepairsProduct::class,'repairs_products','repair_id','repair_id');
    }
}
