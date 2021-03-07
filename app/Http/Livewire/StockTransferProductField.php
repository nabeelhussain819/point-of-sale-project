<?php

namespace App\Http\Livewire;

use App\Helpers\ArrayHelper;
use Livewire\Component;

class StockTransferProductField extends Component
{
    public $products, $formFields = [], $row = 0;

    public function render()
    {
        return view('livewire.transfer-stock.stock-transfer-product-field');
    }

    public function mount($products)
    {
        $this->products = $products;
    }

    public function addRow($row)
    {
        $this->row = $row + 1;
        ArrayHelper::push($this->formFields, $this->row);
    }
}
