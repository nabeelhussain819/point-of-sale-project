<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSerialNumbers extends Model
{
    use HasFactory;
     /**
     * @var array
     */
    protected $fillable = ['store_id', 'product_id', 'serial_no', 'imei_no', 'is_sold', 'created_at', 'updated_at'];

}
