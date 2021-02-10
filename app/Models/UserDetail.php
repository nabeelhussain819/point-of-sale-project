<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $DOB
 * @property boolean $gender
 * @property boolean $active
 * @property string $driver_license
 * @property string $state
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 */
class UserDetail extends Model
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
    protected $fillable = ['user_id', 'first_name', 'last_name', 'phone', 'DOB', 'gender', 'active', 'driver_license', 'state', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
