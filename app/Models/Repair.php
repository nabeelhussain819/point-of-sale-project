<?php

namespace App\Models;

use App\Core\Base;
use App\Helpers\ArrayHelper;
use App\Helpers\DateTimeHelper;
use App\Helpers\GuidHelper;
use App\Observers\FinanceObserver;
use App\Scopes\StoreGlobalScope;
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

    protected $appends = ['remaining'];
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

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreGlobalScope());
    }

    public function products()
    {
        return $this->belongsToMany(RepairsProduct::class, 'repairs_products', 'repair_id', 'repair_id');
    }

    public function relatedProducts()
    {
        return $this->hasMany(RepairsProduct::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules()
    {
        return $this->hasMany('App\Models\RepairsSchedules');
    }

    public function getDaysAttribute()
    {
        if (empty($this->created_at)) {
            return 0;
        }
        return $this->created_at->diffInDays(Carbon::now(), false);
    }

    public function getCreatedAtAttribute($createdAt)
    {

        if (empty($createdAt)) {
            return $this->created_at;
        }
        return Carbon::parse($createdAt)->format(DateTimeHelper::DATE_FORMAT_DEFAULT);
    }

    public function getRemainingAttribute()
    {
        return $this->total_cost - $this->advance_cost;
    }

    public function syncProducts(array $products, $substituteFromInventory = true)
    {
        $products = collect($products)->map(function ($product) use ($substituteFromInventory) {


            $product['product_id'] = isset($product['product_id']) ? Product::getIdByRequest($product['product_id'], ['is_repair' => true]) : null;
            $product['device_type_id'] = isset($product['device_type_id']) ? DevicesType::getIdByRequest($product['device_type_id']) : null;
            $product['issue_id'] = isset($product['issue_id']) ? IssueType::getIdByRequest($product['issue_id']) : null;
            $product['brand_id'] = isset($product['brand_id']) ? Brand::getIdByRequest($product['brand_id']) : null;


            $product['product'] = ArrayHelper::isArray($product['product']) && isset($product['product']) && !empty($product['product']) ? $product['product'][0] : null;

            if ($substituteFromInventory && isset($product['product_id']) && !empty($product['product_id'])) {

                Inventory::pluckSingleQuantity(1, $product['product_id']);
            }

            return ArrayHelper::merge($product, ['guid' => GuidHelper::getGuid()]);

        })->all();

        $this->products()->sync($products);

        return $this;
    }
}
