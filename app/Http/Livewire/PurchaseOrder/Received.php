<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Models\PurchaseOrder;
use Livewire\Component;

class Received extends Component
{
    public $purchaseOrder;

    public function render()
    {
        return view('livewire.purchase-order.received');
    }

    public function mount(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }
}
