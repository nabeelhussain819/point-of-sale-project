<?php

namespace App\Models;

use App\Core\Base;
use App\Traits\InteractWithFindOrCreate;

/**
 * @property integer $id
 * @property string $name
 * @property string $guid
 * @property string $created_at
 * @property string $updated_at
 * @property RepairsProduct[] $repairsProducts
 * @property DevicesTypesBrandsProduct[] $devicesTypesBrandsProducts
 */
class Brand extends Base
{
    use InteractWithFindOrCreate;
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
        return $this->hasMany('App\Models\RepairsProduct');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devicesTypesBrandsProducts()
    {
        return $this->hasMany('App\Models\DevicesTypesBrandsProduct');
    }
}
