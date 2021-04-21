<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\Order;

class OrderObserver
{
    public function saved(Order $order)
    {
        $this->inventoryAdjustment($order);
    }

    private function inventoryAdjustment(Order $order)
    {
        Inventory::serialNumberDetach($order->POSTEDPRODUCTS);
    }
}
