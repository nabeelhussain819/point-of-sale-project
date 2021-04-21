<?php

namespace App\Models;

use App\Core\Base;
use App\Observers\InventoryObserver;
use App\Scopes\StoreGlobalScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $vendor_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property Product $product
 * @property Vendor $vendor
 * @property string quantity
 */
class Inventory extends Base
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    protected $autoBlame = false;
    /**
     * @var array
     */
    protected $fillable = ['product_id', 'vendor_id', 'name', 'quantity', 'lookup', 'description', 'UPC', 'cost', 'extended_cost', 'stock_bin_id', 'created_at', 'store_id', 'updated_at', 'has_serial_number'];

    const
        SCENARIO_TRANSFER = 'TRANSFER',
        SCENARIO_PURCHASE = 'PURCHASE';

    public static $INCOMING_PRODUCTS = false;
    public $OUTGOING_PRODUCTS = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function sales()
    {
        return $this->hasMany(Order::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreGlobalScope);
        Inventory::observe(InventoryObserver::class);
    }

    public static function storeProducts(int $storeId = null, $select = ['id', 'name'])
    {
        if (empty($storeId)) {
            $storeId = Store::currentId();
        }
        return self::getStoreProductsQuery($storeId, $select)->pluck('product');
    }

    public static function getProducts(int $storeId = null, $select = ['id', 'name'])
    {
        return self::getStoreProductsQuery($storeId, $select);
    }

    private static function getStoreProductsQuery(int $storeId = null, $select = ['id', 'name'])
    {
        if (empty($storeId)) {
            $storeId = Store::currentId();
        }
        return Inventory::where('store_id', $storeId)
            ->where('stock_bin_id', 1)
            ->withoutGlobalScope(new StoreGlobalScope)
            ->with(['product' => function (BelongsTo $belongsTO) use ($select) {
                $belongsTO->select($select);
            }])->get();
    }

    public static function storeProductsById(array $products)
    {
        return Inventory::where('store_id', Store::currentId())
            ->whereIn('product_id', $products)
            ->with(['product' => function ($query) {
                $query->select(['id', 'name']);
            }])
            ->get();
    }

    public static function updateProductQuantityByIdAndProductId($id , $product_id, $quantity){
        return Inventory::where(
            [
                ['id' , '=', $id],
                ['product_id',  '=', $product_id],
                ['store_id',  '=', Store::currentId()]
            ]
        )->update(
            array(
                'quantity' => $quantity 
            )
        );
    }

    private function removeStoreScope()
    {
         $this->withoutGlobalScope(new StoreGlobalScope);
         return $this;
    }

    private static function defaultSelect(): array
    {
        return ['id', 'quantity', 'store_id'];
    }

    public static function getProductQuantity($storeId, $productId, $bin = 1)
    {
        return Inventory::select(self::defaultSelect())
            ->withoutGlobalScope(new StoreGlobalScope)
            ->where('store_id', $storeId)
            ->where('product_id', $productId)
            ->where('stock_bin_id',$bin)
            ->first();
    }

    public static function getSerialProducts($productId, $storeId)
    {
        return ProductSerialNumbers::getByStoreId($productId, $storeId);
    }

    public static function getTransferSerialProduct($productId, $transferId)
    {
        return ProductSerialNumbers::getTransferSerialProduct($productId, $transferId);
    }

    public static function getProduct()
    {
        return Inventory::with('product')
            ->where("")
            ->where('store_id', Store::currentId())
            ->first();
    }

    public static function serialNumberDetach($productsData)
    {
        collect($productsData)->filter(function ($inventoryProduct) {
            return !empty($inventoryProduct['serial_number']);
        })->each(function ($inventoryProduct) {
            Inventory::where('store_id', Store::currentId())
                ->where('product_id', $inventoryProduct['product_id'])
                ->get()
                ->each(function (Inventory $inventory) use ($inventoryProduct) {
                    $inventory->OUTGOING_PRODUCTS = true;
                    // because in current scenrio we sell 1 serial product at once
                    $inventory->update(['quantity' => $inventory->quantity - $inventoryProduct['quantity']]); // inventory mai se quantity kam karhe hain
                    ProductSerialNumbers::updateStatusSold($inventoryProduct['product_id'], Store::currentId(), $inventoryProduct['serial_number']);
                });
            return $inventoryProduct;
        });
    }
}
