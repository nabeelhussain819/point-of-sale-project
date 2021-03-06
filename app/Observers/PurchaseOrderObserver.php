<?php

namespace App\Observers;

use App\Models\PurchaseOrder;


class PurchaseOrderObserver
{

    public function saving(PurchaseOrder $purchaseOrder)
    {
        dd("hre");
    }
}
