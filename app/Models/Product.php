<?php

namespace App\Models;

use App\Core\Base;
use App\Scopes\ProductRepairScope;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $department_id
 * @property integer $category_id
 * @property string $name
 * @property string guid
 * @property string $description
 * @property string $UPC
 * @property float $cost
 * @property float $retail_price
 * @property float $min_price
 * @property boolean $taxable
 * @property boolean $active
 * @property string $created_at
 * @property string $updated_at
 * @property Category $category
 * @property Department $department
 * @property Inventory[] $inventories
 * @property boolean $has_serial_number
 */
class Product extends Base
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
    protected $fillable = ['department_id', 'guid',
        'category_id', 'name', 'description', 'UPC', 'cost', 'retail_price',
        'is_repair',
        'min_price', 'taxable', 'active', 'created_at', 'updated_at', 'has_serial_number'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public static function getProductByUPC($upc)
    {
        return Product::where("UPC", $upc)->firstorFail();
    }

    public static function getPrice(int $id)
    {
        $product = Product::select('id', 'cost')->where('id', $id)->firstorFail();
        return $product->cost;
    }

    public function setHasSerialNumberAttribute($hasSerial)
    {
        $this->attributes['has_serial_number'] = $hasSerial === 'on';
    }


    public function devicesTypesBrands()
    {
        return $this->belongsToMany(DevicesTypesBrandsProduct::class);
    }

    public static function defaultSelect()
    {
        return ['id', 'name'];
    }

    public static function get($value): Product
    {
        return Product::where('id', $value)->first();
    }

    public function serials()
    {
        return $this->hasMany(ProductSerialNumbers::class);
    }

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ProductRepairScope());
    }

}
