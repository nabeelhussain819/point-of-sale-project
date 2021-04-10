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
    public $productSerials = [];
    public $showModal = false;
    public $serialFetchParams = [];


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
        $this->formFields[$this->row] = ['quantity' => null, 'product_id' => null, 'error' => false, 'hasSerial' => false];
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
        if (!empty($currentRow['product_id']) && !empty($currentRow['quantity'])) {

            $inventory = Inventory::getProductQuantity($this->storeOutId, $currentRow['product_id']);
            $quantity = $currentRow['quantity'] - $inventory->quantity;
            if ($quantity > 0) {
                $this->formFields[$key]['error'] = true;
                $this->formFields[$key]['message'] = "please reduce {$quantity} quantity for process";
                $this->shouldSubmit = false;
            } else {

                $this->formFields[$key]['error'] = false;
                $this->shouldSubmit = true;
            }

            $this->hasSerial($key, $inventory);
        }
    }

    private function hasSerial($key, $inventory)
    {
        $products = $this->products->pluck('product');
        $product = $products->where('id', $this->formFields[$key]['product_id'])->first();

        $this->formFields[$key]['hasSerial'] = $product->has_serial_number;
    }

    public function removeRow($key)
    {
        unset($this->formFields[$key]);
    }

    public function associateSerial($key)
    {
        $this->showModal = true;
        $this->serialFetchParams = [
            'product_id' => $this->formFields[$key]['product_id'],
            'store_id' => $this->storeOutId,
            'quantity' => $this->formFields[$key]['quantity']
        ];
//        $productId = $this->formFields[$key]['product_id'];
//        $this->formFields[$key]['quantity'];
//
        //$this->getSerialProduct($productId, $this->storeOutId);
    }

//    public function getSerialProduct($productId, $storeId)
//    {
//        $this->productSerials = Inventory::getSerialProducts($productId, $storeId)->paginate(25);
//    }
}
