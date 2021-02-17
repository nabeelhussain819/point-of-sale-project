<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property UserStore[] $userStores
 */
class User extends Authenticatable
{
    use HasRoles;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'email_verified_at', 'remember_token', 'created_at', 'is_changed_password','updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userStores()
    {
        return $this->hasMany(UserStore::class);
    }

    public function userDetail()
    {
        return $this->hasMany(UserDetail::class);
    }
}
