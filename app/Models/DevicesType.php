<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\Base;

/**
 * @property integer $id
 * @property string $name
 * @property string $guid
 * @property string $created_at
 * @property string $updated_at
 * @property RepairsProduct[] $repairsProducts
 * @property DevicesTypesBrandsProduct[] $devicesTypesBrandsProducts
 */
class DevicesType extends Base
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
    protected $fillable = ['name', 'guid', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repairsProducts()
    {
        return $this->hasMany('App\Models\RepairsProduct', 'device_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devicesTypesBrandsProducts()
    {
        return $this->hasMany('App\Models\DevicesTypesBrandsProduct', 'device_type_id');
    }
}
