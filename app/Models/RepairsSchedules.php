<?php

namespace App\Models;

use App\Core\Base;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $repair_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $date_of_payment
 * @property float $received_amount
 * @property float $additional_charge
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property Repair $repair
 */
class RepairsSchedules extends Base
{
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
    protected $fillable = ['repair_id', 'created_by', 'updated_by', 'date_of_payment', 'received_amount', 'comment', 'discount', 'additional_charge', 'created_at', 'updated_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function repair()
    {
        return $this->belongsTo('App\Repair');
    }
}
