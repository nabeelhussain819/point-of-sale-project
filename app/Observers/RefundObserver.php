<?php

namespace App\Observers;

use App\Models\OrderProduct;
use App\Models\Refund;
use Illuminate\Support\Collection;

class RefundObserver
{
    public function creating(Refund $refund)
    {
        $products = $this->getFiltersProductId($refund);
        $productId = $products->pluck('product_id')->toArray();
        $orderProducts = OrderProduct::where('order_id', $refund->order_id)
            ->whereIn('product_id', $productId);
        dd($orderProducts->get());
        //Validate k order mai itne paise hain bhi k nhi
        //        //Create new order
        //        //
    }


    public function updating(Refund $refund)
    {
        dd($refund->products);

        //Validate k order mai itne paise hain bhi k nhi
        //        //Create new order
        //        //
    }

    private function getFiltersProductId(Refund $refund):Collection
    {
        return collect($refund->PostedProducts)
            ->filter(function ($product) {
                return $product['quantity'] > 0;
            });
    }
}
