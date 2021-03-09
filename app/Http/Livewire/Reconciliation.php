<?php

namespace App\Http\Livewire;

use App\Helpers\ArrayHelper;
use App\Models\Inventory;
use App\Models\Reconciliation as ReconciliationModel;
use App\Models\Store;
use Livewire\Component;

class Reconciliation extends Component
{
    public $inputs = [];
    public $i = 0;
    public $products = [];
    public $productPrices = [];
    public $quantity = [];
    public $lookup;
    public $storeProducts = [];
    public $store_id, $name;


    public function render()
    {
        return view('livewire.reconciliation.reconciliation', [
            'stores' => Store::all()
        ]);
    }

    public function submit()
    {
        $this->validate();

        // Execution doesn't reach here if validation fails.

//        Contact::create([
//            'name' => $this->name,
//            'email' => $this->email,
//        ]);
    }

    public function add($i)
    {
        $this->productPrices[$i] = 0;
        $this->quantity[$i] = 0;
        $this->products[$i] = 0;
//        $this->products[$i] = ['physical_quantity' => 0];
        $this->i = $i + 1;
        ArrayHelper::push($this->inputs, $this->i);
    }

    public function storeProducts()
    {
        $this->storeProducts = Inventory::where('store_id', $this->store_id)
            ->get()->pluck('product');

    }

    protected function rules()
    {
        return [
            'name' => 'required|min:6',
        ];
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'store_id' => 'required'
        ]);
        $reconciliation = new ReconciliationModel();
        $reconciliation->fill([
            'store_id' => $this->store_id,
            'name' => $this->name,
            'is_reconciled' => false
        ]);
        $reconciliation->save();
        $reconciliation->reconciliationProducts()->sync($this->products);
        $this->resetFields();
        session()->flash('success', 'Physical Quantity counted');

    }

    public function resetFields()
    {
        //IDK yar heelllll thisis
        $this->name = null;
        $this->store_id = null;
        $this->products = [];
        $this->inputs = [];
        $this->i = 0;
    }

}
