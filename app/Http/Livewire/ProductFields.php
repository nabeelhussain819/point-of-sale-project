<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProductFields extends Component
{
    public $inputs = [];
    public $i = 1;

    public function render()
    {
        return view('livewire.purchase-order.product-fields');
    }

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i; // $i++
        array_push($this->inputs, $i);
    }

    public function store()
    {

        dd("ad");
    }
}
