<?php

namespace App\Models;

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
 */
class Product extends Model
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
    protected $fillable = ['department_id', 'guid','category_id', 'name', 'description', 'UPC', 'cost', 'retail_price', 'min_price', 'taxable', 'active', 'created_at', 'updated_at'];

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


    public static function getPrice(int $id)
    {
        $product = Product::select('id', 'cost')->where('id', $id)->firstorFail();
        return $product->cost;
    }
}
