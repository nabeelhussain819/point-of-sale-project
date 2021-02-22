<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $guid
 * @property string $name
 * @property string $full_name
 * @property string $code
 * @property string $location
 * @property string $timezone
 * @property string $contact_info
 * @property string $primary_phone
 * @property string $fax
 * @property string $description
 * @property boolean $active
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
    protected $fillable = ['guid', 'name', 'full_name', 'city','state','zip','code', 'location', 'timezone', 'contact_info', 'primary_phone', 'fax', 'description', 'active', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userStores()
    {
        return $this->hasMany(UserStore::class);
    }

    public static function code()
    {
        return rand(0,9999);
    }
}
