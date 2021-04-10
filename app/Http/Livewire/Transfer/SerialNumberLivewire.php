<?php

namespace App\Http\Livewire\Transfer;

use App\Models\Inventory;
use Livewire\Component;

class SerialNumberLivewire extends Component
{
    public $serials = [];

    public function render()
    {
        return view('livewire.transfer.serial-number-livewire', ['data' => $this->serials]);
    }

    public function mount($params)
    {
        $this->getSerialProduct($params['product_id'], $params['store_id']);
    }

    public function getSerialProduct($productId, $storeId)
    {
        $this->serials = Inventory::getSerialProducts($productId, $storeId)->get(25);
    }
}
