<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\Base;
/**
 * @property integer $id
 * @property integer $device_type_id
 * @property integer $brand_id
 * @property integer $product_id
 * @property integer $issue_id
 * @property string $description
 * @property float $total_cost
 * @property string $device_unique_number
 * @property string $guid
 * @property string $created_at
 * @property string $updated_at
 * @property DevicesType $devicesType
 * @property Brand $brand
 * @property Product $product
 * @property IssueType $issueType
 */
class RepairsProduct extends Base
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
    protected $fillable = ['device_type_id', 'brand_id', 'product_id', 'issue_id', 'description', 'total_cost', 'device_unique_number', 'guid', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function devicesType()
    {
        return $this->belongsTo('App\Models\DevicesType', 'device_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function issueType()
    {
        return $this->belongsTo('App\Models\IssueType', 'issue_id');
    }
}
