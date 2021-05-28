<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/22/2021
 * Time: 12:34 AM
 */

namespace App\Traits;


use App\Helpers\ArrayHelper;
use App\Helpers\StringHelper;
use App\Models\Customer;

trait InteractWithFindOrCreate
{
    public static function FindOrCreateByName(string $name, array $attributes = []): self
    {
        $name = StringHelper::lower($name);

        $model = self::firstOrNew(['name' => $name]);
        if (empty($model->id)) {
            $model->fill(ArrayHelper::merge(['name' => $name], $attributes));
            $model->save();
            return $model;
        }
        return $model;
    }

    public static function getIdByRequest($key, array $attributes = []): int
    {
        $key = self::_getKey($key);

        if (StringHelper::isInt($key)) {
            return $key;
        }

        $model = self::FindOrCreateByName($key, $attributes);

        return $model->id;
    }

    private static function _getKey($key)
    {
        if (ArrayHelper::isArray($key)) {
            if (isset($key[0]))
                return $key[0];
        }
        return $key;
    }
}