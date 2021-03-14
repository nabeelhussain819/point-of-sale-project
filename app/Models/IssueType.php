<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $guid
 * @property string $created_at
 * @property string $updated_at
 * @property RepairsProduct[] $repairsProducts
 */
class IssueType extends Model
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
    protected $fillable = ['name', 'guid', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repairsProducts()
    {
        return $this->hasMany('App\Models\RepairsProduct', 'issue_id');
    }
}
