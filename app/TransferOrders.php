<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $transfer_by
 * @property integer $received_by
 * @property integer $store_in_id
 * @property integer $store_out_id
 * @property string $request_id
 * @property string $transfer_date
 * @property string $received_date
 * @property string $created_by
 * @property string $updated_by
 * @property Store_out $store_out
 * @property store_in $store_in
 * @property User $user
 * @property recviedBy $recviedBy
 */
class TransferOrders extends Model
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
    protected $fillable = ['transfer_by', 'received_by', 'store_in_id', 'store_out_id', 'request_id', 'transfer_date', 'received_date', 'created_by', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function storeOut()
    {
        return $this->belongsTo('App\Store', 'store_in_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function storeIn()
    {
        return $this->belongsTo('App\Store', 'store_out_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transferBy()
    {
        return $this->belongsTo('App\User', 'transfer_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receivedBy()
    {
        return $this->belongsTo('App\User', 'received_by');
    }
}
