<?php

namespace App\Observers;

use App\Helpers\Tax;
use App\Models\Inventory;
use App\Models\OrderProduct;
use App\Models\ProductSerialNumbers;
use App\Models\Refund;
use App\Models\Store;
use App\Models\Type;
use App\Scopes\StockBinGlobalScope;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RefundObserver
{
    public function created(Refund $refund)
    {
        // some time from front  quantity 1 comming from

        $products = $this->getFiltersProductId($refund);

        $this->attachBackProductToInventory($refund, $products);
        $this->updateOrderTotal($refund, $products);
    }


    public function updating(Refund $refund)
    {
        //Validate k order mai itne paise hain bhi k nhi
        //        //Create new order
        //        //
    }

    private function getFiltersProductId(Refund $refund): Collection
    {
        return collect($refund->PostedProducts)
            ->filter(function ($product) {
                return $product['quantity'] > 0;
            });
    }

    private function attachBackProductToInventory(Refund $refund, $products)
    {
        //  serial number add to inventory
        collect($products)->filter(function ($product) {
            return empty($product['serial_no']);
        })
            ->each(function ($product) {
                Inventory::
                withoutGlobalScope(StockBinGlobalScope::class)
                    ->updateOrCreate([
                        'product_id' => $product['product_id'],
                        'store_id' => Store::currentId(),
                        'stock_bin_id' => Type::REFUND,
                    ], [
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'stock_bin_id' => Type::REFUND,
                        'store_id' => Store::currentId(),
                    ]);
            });

        // serial number add to inventory
        collect($products)->filter(function ($product) {
            return !empty($product['serial_no']);
        })
            ->each(function ($product) use ($refund) {
                Inventory::
                withoutGlobalScope(StockBinGlobalScope::class)
                    ->updateOrCreate([
                        'product_id' => $product['product_id'],
                        'store_id' => Store::currentId(),
                        'stock_bin_id' => Type::REFUND,
                    ], [
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'stock_bin_id' => Type::REFUND,
                        'store_id' => Store::currentId(),
                    ]);

                ProductSerialNumbers::updateStatusSold($product['product_id'], Store::currentId(), $product['serial_no'], [
                    'subject' => Refund::class,
                    'subject_id' => $refund->id,
                    'subject_data' => $refund
                ], false);
            });
    }

    private function updateOrderTotal(Refund $refund, $products)
    {
        $this->updateOrderProducts($refund, $products);
        $priceDelta = $refund->order->sub_total - $refund->return_cost;
        if ($priceDelta < 0) {
            throw  new ConflictHttpException('Refund price not less than the order price');
        }

        $discount = 0;
        $orderTotal = 0;
        $refund->order->products->each(function (OrderProduct $orderProduct) use (&$discount, &$orderTotal) {
            $total = $orderProduct->quantity * $orderProduct->total;
            $orderTotal += $total;
            $retailPrice = $orderProduct->quantity * $orderProduct->retail_price;
            $discount += $retailPrice - $total;
        });
        $tax = Tax::getByPercentage($priceDelta, $refund->order->tax);

        $total = $orderTotal + $tax;


        $refund->previous_sub_total = $refund->order->without_tax;
        $refund->previous_total = $refund->order->sub_total;
        $refund->previous_discount_amount = $refund->order->discount;

        $refund->order->sub_total = $total;
        $refund->order->discount = $discount;
        $refund->order->without_tax = $orderTotal;

        $refund->saveQuietly();
        $refund->order->saveQuietly();

    }

    /**
     * @param Refund $refund
     * @param $products
     */
    private function updateOrderProducts(Refund $refund, $products)
    {
        $keys = $products->keys(); // keys are the order_products id

        $refund->order->products->whereIn('id', $keys)
            ->each(function (OrderProduct $orderProduct) use ($products) {
                $postedProduct = $products[$orderProduct->id];
                $orderProduct->quantity = $orderProduct->quantity - $postedProduct['quantity'];
                if ($orderProduct->quantity < 0) {
                    $productName = $orderProduct->product->name;

                    throw  new ConflictHttpException($productName . " quantity of refund should not be less than 0");
                }
                $orderProduct->saveQuietly();
            });
    }
}
