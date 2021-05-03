<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\Store;

class OrderObserver
{
    public function creating(Order $order)
    {
        $order->store_id = Store::currentId();
    }

    public function saved(Order $order)
    {
        $this->inventoryAdjustment($order);
    }

    private function inventoryAdjustment(Order $order)
    {
        Inventory::serialNumberDetach($order->POSTEDPRODUCTS, $this->getLogData($order));
    }

    private function getLogData(Order $order): array
    {
        if (!empty($order->LOGDATA) && !isset($order->LOGDATA['subject_id'])) {
            $order->LOGDATA['subject_id'] = $order->id;
        }
        return $order->LOGDATA;
    }
}
