<?php

namespace App\Http\Livewire;

use App\Helpers\ArrayHelper;
use App\Models\StockTransferProduct;
use Livewire\Component;

class StockTransferProductField extends Component
{
    public $products, $formFields = [], $row = 0;

    public function render()
    {
        return view('livewire.transfer-stock.stock-transfer-product-field');
    }

    public function mount($products, $dbProducts = null)
    {
        $this->products = $products;

        if (!empty($dbProducts)) {
            $this->formFields = collect($dbProducts)->map(function (StockTransferProduct $product) {
                return $product->getAttributes();
            })->all();
        }
    }

    public function addRow($row)
    {
        $this->row = $row + 1;
        ArrayHelper::push($this->formFields, $this->row);
    }

    public function deleteRow($row)
    {
        unset($this->row);
    }
}
