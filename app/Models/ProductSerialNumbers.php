<?php

namespace App\Models;

use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ProductSerialNumbers extends Model
{
    use HasFactory;
    /**
     * @var array
     */
    protected $fillable = ['store_id', 'product_id', 'serial_no', 'imei_no', 'is_sold', 'created_at', 'updated_at', 'purchase_order_id', 'stock_transfer_id'];

    public static function getByStoreId(int $productId, int $storeId)
    {
        return ProductSerialNumbers::select(['id', 'store_id', 'product_id', 'serial_no',])
            ->where('store_id', $storeId)
            ->where('is_sold', '!=', true)
            ->where('product_id', $productId);
    }

    public static function getTransferSerialProduct(int $productId, int $transferId)
    {
        return ProductSerialNumbers::select(['id', 'store_id', 'product_id', 'serial_no',])
            ->where('stock_transfer_id', $transferId)
            ->where('product_id', $productId);
    }

    public static function updateStatusSold(int $productId, int $storeId, string $serialNo)
    {
        return ProductSerialNumbers::where('store_id', $storeId)
            ->where('product_id', $productId)
            ->where('serial_no', $serialNo)
            ->update(['is_sold' => true]);
    }

    /**
     * @param int $productId
     * @param string $serialNo
     * @return bool
     * @throws NotFound
     */
    public static function isAvailable(int $productId, string $serialNo): bool
    {
        $product = ProductSerialNumbers::where('product_id', $productId)
            ->where('serial_no', $serialNo)
            ->first();
        if (empty($product)) {
            throw new NotFound('serial number does not exist');
        }

        if ($product->is_sold) {
            throw new ConflictHttpException('This serial number has been sold');
        }
        return !$product->is_sold;
    }
}
