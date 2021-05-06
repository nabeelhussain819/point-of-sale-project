<?php


namespace App\Helpers;


class Tax
{
    public static function getByPercentage($value, $percentage = 0)
    {
        return ($value / 100) * $percentage;
    }
}