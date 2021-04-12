<?php

namespace App\Models;

use App\Core\Base;
use Carbon\Carbon;

/**
 * @property integer $id
 * @property integer $customer_id
 * @property string $status
 * @property float $total_cost
 * @property float $advance_cost
 * @property string $guid
 * @property carbon $created_at
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
    protected $fillable = ['customer_id', 'status', 'total_cost', 'advance_cost', 'guid', 'store_id', 'created_at', 'updated_at'];

    protected $appends = ['days', 'remaining'];
    const IN_PROGRESS_STATUS = "INPROGRESS";
    const IN_COMPLETED_STATUS = "COMPLETED";
    const IN_COLLECTED_STATUS = "COLLECTED";
    const IN_CANCELLED_STATUS = "CANCELLED";

    public static function statuses()
    {
        return [
            ['id' => self::IN_PROGRESS_STATUS, 'name' => 'In Progress'],
            ['id' => self::IN_COMPLETED_STATUS, 'name' => 'Ready For Pickup'],
            ['id' => self::IN_COLLECTED_STATUS, 'name' => 'Collected'],
            ['id' => self::IN_CANCELLED_STATUS, 'name' => 'Cancelled']
        ];
    }
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

    public function relatedProducts(){
        return $this->hasMany(RepairsProduct::class);
    }

    public function getDaysAttribute()
    {
        return  $this->created_at->diffInDays(Carbon::now(), false);
    }

    public function getRemainingAttribute()
    {
        return $this->total_cost - $this->advance_cost;
    }
}
