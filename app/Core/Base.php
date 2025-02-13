<?php

namespace App\Core;

use App\Helpers\GuidHelper;
use App\Helpers\StringHelper;
use App\Traits\LogsActivityCustom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * @property integer $id
 * @property string $name
 * @properties AttributesValue[] $attributesValues
 * @properties ProductAttribute[] $productAttributes
 * @mixin Builder
 * /**
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder create(array $attributes = [])
 * @method public Builder update(array $values)
 * @method static Builder find($values)
 * @method  Builder fill()
 * @method static Builder delete()(array $values)
 * @method static Builder with($relations)
 *
 * /**
 * App\Core\Base
 *
 * @mixin \Eloquent
 */
class Base extends Model
{
    use LogsActivityCustom;
    protected $autoBlame = true;
    protected $hasGuid = true;

    protected static $submitEmptyLogs = false;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::creating(function (Base $baseModel) {

            if ($baseModel->hasGuid && empty($baseModel->guid)) {

                $baseModel->setAttribute('guid', GuidHelper::getGuid());

            }
            if ($baseModel->autoBlame) {
                if (empty($baseModel->created_by) || empty($baseModel->updated_by)) {
                    $baseModel->setAttribute('created_by', Auth::user()->id);
                    $baseModel->setAttribute('updated_by', Auth::user()->id);
                }
            }
            if (!empty($baseModel->name)) {
                $baseModel->name = StringHelper::lower($baseModel->name);
            }
        });
    }
}