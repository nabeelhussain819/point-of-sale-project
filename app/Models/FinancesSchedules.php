<?php

namespace App\Models;

use App\Core\Base;
use App\Observers\FinanceObserver;
use App\Scopes\StoreGlobalScope;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $finance_id
 * @property string $date_of_payment
 * @property string $due_date
 * @property float $amount
 * @property float $received_amount
 * @property float $received_date
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property Finance $finance
 */
class FinancesSchedules extends Base
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
    protected $fillable = [
        'finance_id', 'date_of_payment', 'due_date', 'amount',
        'received_amount', 'received_date', 'status', 'created_at',
        'updated_at', 'comment', 'pay_by_card'
    ];
    protected $casts = [
        'date_of_payment' => 'datetime:Y-m-d (g:i A)',
    ];
    protected $dates = ['due_date', 'received_date', 'date_of_payment'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function finance()
    {
        return $this->belongsTo('App\Finance');
    }


//    public function getDateOfPaymentAttribute($dateOfPayment)
//    {
//        return $dateOfPayment->formate("D/M/YY");
//    }
}
