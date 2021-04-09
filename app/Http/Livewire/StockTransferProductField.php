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
    public $transfer;
    public $isCreated = false;

    public function render()
    {
        return view('livewire.transfer-stock.stock-transfer-product-field');
    }

    public function mount($stores, $transfer = null)
    {
//        $this->products = $products;
        $this->stores = $stores;
        if (!empty($transfer)) {
            $this->transfer = $transfer;
            $this->formFields = collect($transfer->products)->map(function (StockTransferProduct $product) {
                return $product->getAttributes();
            })->all();
            $this->storeOutId = $transfer->store_out_id;
            $this->storeOutSelect();
            $this->isCreated = true;
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
        $this->products = Inventory::getProducts($this->storeOutId);
    }
}
