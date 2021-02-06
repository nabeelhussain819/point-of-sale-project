<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $guid
 * @property string $location
 * @property string $created_at
 * @property string $updated_at
 * @property UserStore[] $userStores
 */
class Store extends Model
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
    protected $fillable = ['name', 'guid', 'location', 'description','created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userStores()
    {
        return $this->hasMany('App\Models\UserStore');
    }
}
