<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $store_id
 * @property string $created_at
 * @property string $updated_at
 * @property Store $store
 * @property User $user
 */
class UserStore extends Model
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
    protected $fillable = ['user_id', 'store_id','role_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public static function getCurrentUserStore()
    {
        return UserStore::where('user_id', auth()->user()->id)->get();
    }
}
