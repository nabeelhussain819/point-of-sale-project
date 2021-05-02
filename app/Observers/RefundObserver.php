<?php

namespace App\Observers;

use App\Models\OrderProduct;
use App\Models\Refund;
use Illuminate\Support\Collection;

class RefundObserver
{
    public function creating(Refund $refund)
    {
        // some time from front  quantity 1 comming from
        $products = $this->getFiltersProductId($refund);
        dd($products);

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

    private function getFiltersProductId(Refund $refund):Collection
    {
        return collect($refund->PostedProducts)
            ->filter(function ($product) {
                return $product['quantity'] > 0;
            });
    }
}
