<?php

namespace App\Observers;

use App\Models\Inventory;
use mysql_xdevapi\Exception;

class InventoryObserver
{
    public function saving(Inventory $inventory)
    {
        //@todo show specific
        if (!$inventory->OUTGOING_PRODUCTS) {
            $inventory->quantity = $inventory->getOriginal('quantity') + $inventory->quantity;
        }
        if ($inventory < 0) {
            throw new Exception("inventory has been less than zero form that operation please check the case ");
        }
    }
}