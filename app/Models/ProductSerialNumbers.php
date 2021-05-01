<?php

namespace App\Models;

use App\Core\Base;
use App\Observers\SerialLogObserver;
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

    public static function updateStatusSold(int $productId, int $storeId, string $serialNo, Order $order)
    {

        $product = ProductSerialNumbers::where('store_id', $storeId)
            ->where('product_id', $productId)
            ->where('serial_no', $serialNo)
            ->first();

        if (empty($product)) {
            throw new NotFoundHttpException("No product found against {$serialNo} on product Number {$productId}");
        }
        if (!empty($product) && $product->is_sold) {
            throw new ConflictHttpException("{$product->serial_no} is sold");
        }
        $product->subject = Order::class;
        $product->subject_id = $order->id;
        $product->subject_data = $order;
        return $product->update(['is_sold' => true]);
    }

    public static function boot()
    {
        parent::boot();

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
        return !$product->is_sold;
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
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
                    'serial_no' => $serial,
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
}
