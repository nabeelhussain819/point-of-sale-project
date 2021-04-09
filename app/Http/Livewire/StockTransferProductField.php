<?php

namespace App\Http\Livewire;

use App\Models\Inventory;
use App\Models\StockTransferProduct;
use Livewire\Component;

class StockTransferProductField extends Component
{
    public $products, $formFields = [], $row = 0, $stores;
    public $storeOutId;
    public $transfer;
    public $isCreated = false;
    public $selectedProduct;
    public $shouldSubmit = true;

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
        $this->formFields[$this->row] = ['quantity' => null, 'product' => null, 'error' => false];
        $this->row = $row + 1;
    }

    public function deleteRow($row)
    {
        unset($this->row);
    }

    public function storeOutSelect()
    {
        $this->products = Inventory::getProducts($this->storeOutId);
    }

    public function validateProduct($key)
    {
        $currentRow = $this->formFields[$key];
        if (!empty($currentRow['product']) && !empty($currentRow['quantity'])) {

            $inventory = Inventory::getProductQuantity($this->storeOutId, $currentRow['product']);
            $quantity = $currentRow['quantity'] - $inventory->quantity;
            if ($quantity > 0) {
                $this->formFields[$key]['error'] = true;
                $this->formFields[$key]['message'] = "please reduce {$quantity} quantity for process";
                $this->shouldSubmit = false;
            } else {

                $this->formFields[$key]['error'] = false;
                $this->shouldSubmit = true;

            }
        }
    }

    public function removeRow($key)
    {
        unset($this->formFields[$key]);
    }
}
