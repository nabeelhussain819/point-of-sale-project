<?php

namespace App\Observers;

use App\Models\Inventory;

class InventoryObserver
{
    public function saving(Inventory $inventory)
    {
        $inventory->quantity = $inventory->getOriginal('quantity') + $inventory->quantity;
    }
}
