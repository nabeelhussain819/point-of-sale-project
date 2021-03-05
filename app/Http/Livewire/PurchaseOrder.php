<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PurchaseOrder extends Component
{
    public $vendors;

    public function render()
    {
        return view('livewire.purchase-order.index');
    }

    public function vendors($vendors)
    {
        $this->vendors = $vendors;
    }

    public function save()
    {
        $this->validate(['store_id' => 'required']);
    }
}
