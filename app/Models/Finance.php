<?php

namespace App\Models;

use App\Observers\FinanceObserver;
use App\Scopes\StoreGlobalScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $customer_id
 * @property integer $store_id
 * @property integer $type
 * @property string $status
 * @property float $total
 * @property float $advance
 * @property float $payable
 * @property float $duration_period
 * @property string $duration_period_unit
 * @property float $duration_due_date
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property float $installment
 * @property string $created_at
 * @property string $updated_at
 *  @property string $serial_number
 *
 * @property string $customer_name
 * @property int $product_id
 * @property int $order_id
 * @property Customer $customer
 * @property Store $store
 * @property FinancesSchedule[] $financesSchedules
 */
class Finance extends Model
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
    protected $fillable = ['customer_id', 'store_id', 'product_id', 'type',
        'status', 'total', 'advance', 'payable', 'duration_period', 'duration_period_unit',
        'duration_due_date', 'start_date', 'end_date', 'installment', 'created_at', 'updated_at',
        'customer_name', 'customer_phone', 'customer_address', 'customer_card_number',
        'customer_card_expiry', 'customer_card_ccv',
        'comments',
        'serial_number'
    ];

    protected $Date = ['customer_card_expiry'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    protected $dates = ['start_date', 'end_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function financesSchedules()
    {
        return $this->hasMany('App\FinancesSchedule');
    }

    public function schedules()
    {
        return $this->belongsToMany(FinancesSchedules::class, 'finances_schedules', 'finance_id', 'finance_id');
    }

    public function releatedSchedules()
    {
        return $this->hasMany(FinancesSchedules::class);
    }

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreGlobalScope());
        Finance::observe(FinanceObserver::class);
    }

    public function withCustomer()
    {
        $this->load(['customer' => function (BelongsTo $query) {
            $query->select(["id", "name", "phone"]);
        }]);
        return $this;
    }

    public function withProduct()
    {
        $this->load(['product' => function (BelongsTo $query) {
            $query->select(["id", "name"]);
        }]);
        return $this;
    }

    public function withSchedules()
    {
        $this->load('releatedSchedules');
        return $this;
    }

    public function order()
    {
        $this->belongsTo(Order::class);
    }

    public function getTypeAttribute($type)
    {
        return $type === 1 ? "Layaway" : "In Store Finance";
    }
}
