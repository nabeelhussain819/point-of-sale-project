<?php

namespace App\Observers;

use App\Models\Inventory;

class InventoryObserver
{
    public function saving(Inventory $inventory)
    {
        if (!$inventory->OUTGOING_PRODUCTS) {
            $inventory->quantity = $inventory->getOriginal('quantity') + $inventory->quantity;
        }
    }
}
