<?php

namespace App\Models;

use App\Core\Base;
use App\Helpers\ArrayHelper;
use App\Observers\SerialLogObserver;
use App\Scopes\StockBinReturnVendorScope;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductSerialNumbers extends Base
{
    use HasFactory;

    protected $hasGuid = false;

    public $subject, $subject_id, $subject_data;// setting the subject to the log

    /**
     * @var array
     */
    protected $fillable = ['store_id', 'product_id', 'return_to_vendor', 'serial_no', 'imei_no', 'is_sold', 'created_at', 'updated_at', 'stock_bin_id', 'purchase_order_id', 'stock_transfer_id'];

    public static function getByStoreId(int $productId, int $storeId)
    {
        return ProductSerialNumbers::select(['id', 'store_id', 'product_id', 'serial_no',])
            ->where('store_id', $storeId)
            ->where('is_sold', '!=', true)
//            ->where('stock_bin_id',1)
            ->where('product_id', $productId);
    }

    public static function getTransferSerialProduct(int $productId, int $transferId)
    {
        return ProductSerialNumbers::select(['id', 'store_id', 'product_id', 'serial_no',])
            ->where('stock_transfer_id', $transferId)
            ->where('product_id', $productId);
    }

    public static function getPurchaseProduct(int $productId, int $purchase)
    {
        return ProductSerialNumbers::select(['id', 'store_id', 'product_id', 'serial_no',])
            ->where('purchase_order_id', $purchase)
            ->where('product_id', $productId);
    }

    public static function updateStatusSold(int $productId, int $storeId, string $serialNo, array $subject, bool $isSold = true, $attributes = [])
    {
        $product = ProductSerialNumbers::where('store_id', $storeId)
            ->where('product_id', $productId)
            ->where('serial_no', $serialNo)
            ->first();

        if (empty($product)) {
            throw new NotFoundHttpException("No product found against {$serialNo} on product Number {$productId}");
        }

        if (!empty($product) && $product->is_sold && $isSold) {
            throw new ConflictHttpException("{$product->serial_no} is sold");
        }
        $product->subject = $subject['subject'];
        $product->subject_id = $subject['subject_id'];
        $product->subject_data = $subject['subject_data'];
        return $product->update(ArrayHelper::merge(['is_sold' => $isSold], $attributes));
    }

    public static function boot()
    {
        parent::boot();
        ProductSerialNumbers::observe(StockBinReturnVendorScope::class);
        ProductSerialNumbers::observe(SerialLogObserver::class);
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

        if ($product->return_to_vendor) {
            throw new ConflictHttpException('This serial return to vendor');
        }
        return !$product->is_sold;
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function bin()
    {
        return $this->belongsTo(StockBin::class, 'stock_bin_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }


    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class);
    }

    public function track()
    {
        return $this->hasMany(SerialLogs::class, 'product_serial_number_id', 'id');
    }

    public function withProduct()
    {
        $this->load(['product' => function (BelongsTo $belongsTo) {
            $belongsTo->select('id', 'name');
        }]);

        return $this;
    }

    public function withTransfer()
    {
        return $this->load(['stockTransfer' => function (BelongsTo $belongsTo) {
            $belongsTo->select(['id', 'store_in_id', 'request_id', 'store_out_id', 'transfer_date', 'received_date'])
                ->with(['storeOut' => function (BelongsTo $belongsTo) {
                    $belongsTo->select(['id', 'name']);
                },
                    'storeIn' => function (BelongsTo $belongsTo) {
                        $belongsTo->select(['id', 'name']);
                    }]);
        }]);
    }

    public function withPurchaseOrder()
    {
        return $this->load(['purchaseOrder' => function (BelongsTo $belongsTo) {
            //$belongsTo->select(['id', 'store_in_id', 'request_id', 'store_out_id', 'transfer_date', 'received_date']);
        }]);
    }

    public static function syncSerialWIthPurchaseOrder(array $serialProducts, PurchaseOrder $purchaseOrder): array
    {
        $products = [];
        collect($serialProducts)->each(function (&$productSerials, $productId) use (&$products, $purchaseOrder) {

            collect($productSerials)->each(function ($serial) use (&$products, $productId, $purchaseOrder) {

                $product = [
                    'product_id' => $productId,
                    'store_id' => $purchaseOrder->store_id,
                    'serial_no' => preg_replace('/\s+/', ' ', $serial),
                    'purchase_order_id' => $purchaseOrder->id,
                ];

                $productSerial = new ProductSerialNumbers();
                $productSerial->fill($product);
                $productSerial->subject = PurchaseOrder::class;
                $productSerial->subject_id = $purchaseOrder->id;
                $productSerial->save();
                $products[] = $productSerial;
            });

        });
        return $products;
    }

    public static function changeBins(array $products, $bin, array $subject, $isReturnToVendor = false)
    {
        collect($products)->each(function ($product) use ($subject, $isReturnToVendor, $bin) {
            $product = self::where('id', $product['id'])->first();
            $product->subject = $subject['subject'];
            $product->subject_id = $subject['subject_id'];
            $product->subject_data = $subject['subject_data'];
            $product->update(['is_sold' => false, 'return_to_vendor' => $isReturnToVendor, 'stock_bin_id' => $bin]);
        });
    }
}
