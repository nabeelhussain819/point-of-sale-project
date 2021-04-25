<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $name
 * @property string $alias
 * @property string $color
 * @property boolean $system
 * @property string $model
 * @property boolean $active
 * @property string $created_at
 * @property string $updated_at
 * @property Finance[] $finances
 */
class Status extends Model
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
    protected $fillable = ['created_by', 'updated_by', 'name', 'alias', 'color', 'system', 'model', 'active', 'created_at', 'updated_at'];

    Const FINANCE_PENDING = 1;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function finances()
    {
        return $this->hasMany('App\Models\Finance');
    }
}
