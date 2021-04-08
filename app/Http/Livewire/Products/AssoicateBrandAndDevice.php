<?php

namespace App\Http\Livewire\Products;

use App\Models\Brand;
use App\Models\DevicesType;
use App\Models\DevicesTypesBrandsProduct;
use App\Models\Product;
use Livewire\Component;

class AssoicateBrandAndDevice extends Component
{
    public $device_type_id, $brand_id, $product_id;
    public $updateMode = false;
//    public $devices;

    public function render()
    {
        return view('livewire.products.assoicate-brand-and-device',
            ['associations' => DevicesTypesBrandsProduct::paginate(10),
                'devicesTypes' => DevicesType::all(),
                'products' => Product::all(),
                'brands' => Brand::all()]
        );
    }

    public function mount()
    {
//        $this->devices = DevicesTypesBrandsProduct::paginate(10);
//        dd($this->devices);
    }

    public function store()
    {
        $this->validate([
            'device_type_id' => 'required',
            'brand_id' => 'required',
            'product_id' => 'required',
        ]);

        DevicesTypesBrandsProduct::create([
            'device_type_id' => $this->device_type_id,
            'brand_id' => $this->brand_id,
            'product_id' => $this->product_id,
        ]);
        $this->resetField();
        session()->flash('success', 'Added');
    }

    public function resetField()
    {
        $this->name = null;
    }

    public function edit(DevicesType $devicesType)
    {
        $this->selected_id = $devicesType->id;
        $this->name = $devicesType->name;
        $this->updateMode = true;
    }

    public function update(DevicesType $devicesType)
    {
        $this->validate([
            'name' => 'required',
        ]);

        $devicesType->update([
            'name' => $this->name,
        ]);
        $this->updateMode = false;
        $this->resetField();
        session()->flash('success', 'Customer Updated');
    }

    /**
     * @param DevicesType $devicesType
     * @throws \Exception
     */
    public function delete(DevicesType $devicesType)
    {
        $devicesType->delete();
        session()->flash('success', 'Customer Deleted');
    }
}
