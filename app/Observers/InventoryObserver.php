<?php

namespace App\Observers;

use App\Models\Inventory;

class InventoryObserver
{
    /**
     * @param Inventory $inventory
     * @throws \Exception
     */
    public function saving(Inventory $inventory)
    {
        //@todo show specific
        if (!$inventory->OUTGOING_PRODUCTS) {
            $inventory->quantity = $inventory->getOriginal('quantity') + $inventory->quantity;
        }
        if ($inventory->quantity < 0) {
            $productName = $inventory->product->name;

            throw new \Exception("in inventory {$productName} has been less than zero form that operation please adjust the inventory first ");
        }
    }
}