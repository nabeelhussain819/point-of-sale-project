<?php

namespace App\Http\Livewire;

use App\Helpers\ArrayHelper;
use App\Models\Product;
use Livewire\Component;

class ProductFields extends Component
{
    public $inputs = [];
    public $i = 0;
    public $products = [];
    public $productPrices = [];
    public $quantity = [];
    public $lookup;

    public function render()
    {
        return view('livewire.purchase-order.product-fields');
    }

    public function mount()
    {

    }

    public function add($i)
    {
        $this->productPrices[$i] = 0;
        $this->quantity[$i] = 0;
        $this->products[$i] = 0;
        $this->products[$i] = ['price' => 0];
        $this->i = $i + 1;
        ArrayHelper::push($this->inputs, $this->i);
    }

    public function lookUp($key)
    {
        $this->products[$key]['product_id'] = $this->products[$key]['lookup'];
        $this->setPrice($key);
    }

    public function store()
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

    public function search()
    {
    }
}
