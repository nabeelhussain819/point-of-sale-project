<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\OrderProduct;
use App\Models\ProductSerialNumbers;
use App\Models\Refund;
use App\Models\Store;
use App\Models\Type;
use Illuminate\Support\Collection;

class RefundObserver
{
    public function created(Refund $refund)
    {
        // some time from front  quantity 1 comming from
        $products = $this->getFiltersProductId($refund);

        // non serial number add to inventory
        collect($products)->filter(function ($product) {
            return empty($product['serial_no']);
        })
            ->each(function ($product) {
                Inventory::updateOrCreate([
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
                Inventory::updateOrCreate([
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

//        Inventory::where
        //Validate k order mai itne paise hain bhi k nhi
        //        //Create new order
        //        //
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
}
