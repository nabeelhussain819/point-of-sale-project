<?php

namespace App\Http\Livewire;

use App\Helpers\ArrayHelper;
use App\Models\Inventory;
use App\Models\StockTransferProduct;
use Livewire\Component;

class StockTransferProductField extends Component
{
    public $products, $formFields = [], $row = 0, $stores;
    public $storeOutId;

    public function render()
    {
        return view('livewire.transfer-stock.stock-transfer-product-field');
    }

    public function mount($stores, $dbProducts = null)
    {
//        $this->products = $products;
        $this->stores = $stores;
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

    public function storeOutSelect()
    {
        $this->products = Inventory::storeProducts($this->storeOutId);
    }
}
