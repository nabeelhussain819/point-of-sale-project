<?php

namespace App\Http\Livewire;

use App\Helpers\ArrayHelper;
use App\Helpers\StringHelper;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersProduct;
use Livewire\Component;

class ProductFields extends Component
{
    public $inputs = [];
    public $i = 1;
    public $products = [];
    public $productPrices = [];
    public $quantity = [];
    public $lookup;
    public $productDropDown;
    public $isCreated = false;


    public function render()
    {
        return view('livewire.purchase-order.product-fields');
    }


    public function mount(PurchaseOrder $purchaseOrder)
    {
        if (!empty($purchaseOrder->products->first())) {
            $this->isCreated = true;
            $purchaseOrder->products->each(function (PurchaseOrdersProduct $product, $index) {
                $this->i = $index;
                $this->products[$index] =
                    [
                        'price' => $product->price,
                        'product_id' => $product->product_id,
                        'quantity' => $product->quantity,
                        'lookup' => $product->product_id,
                    ];
                $this->setQuantity($index);

                $this->i = $index + 1;
                ArrayHelper::push($this->inputs, $this->i);
            });
        }

        $this->productDropDown = Product::all();
    }

    public function add($i)
    {
        $this->productPrices[$i] = 0;
        $this->quantity[$i] = 0;
        $this->products[$i] = 0;
        $this->products[$i] = ['price' => 0, 'lookUp' => 0];
        $this->i = $i + 1;
        //  ArrayHelper::push($this->inputs, $this->i);

    }

    public function lookUp($key)
    {
        if (!empty($this->products[$key]['lookup'])) {
            $productId = $this->products[$key]['lookup'];
            if (!StringHelper::isInt($productId)) {
                $product = Product::getProductByUPC($productId);
                $productId = $product->id;
            }
            $this->products[$key]['product_id'] = $productId;
            $this->setPrice($key);
        }
    }

    public function store()
    {

    }

    private function settingProducts(int $index, Product $product)
    {

    }

    public function setPrice($i)
    {
        $product = Product::getPrice($this->products[$i]['product_id']);
        $this->products[$i]['price'] = $product;
        $this->products[$i]['quantity'] = 1;
        $this->productPrices[$i] = 0;
        $this->setQuantity($i);
    }

    public function quantity($i)
    {
        $this->setQuantity($i);
    }

    private function setQuantity($i)
    {
        if (!empty($this->products[$i]['quantity'])) {
            $this->productPrices[$i] = $this->products[$i]['quantity'] * $this->products[$i]['price'];
        }
    }

    public function removeRow($key)
    {
        $products = $this->products;
        unset($products[$key]);
        $this->products = $products;

    }


    public function search()
    {
    }
}
