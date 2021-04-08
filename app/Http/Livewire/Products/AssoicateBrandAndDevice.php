<?php

namespace App\Http\Livewire\Products;

use App\Models\DevicesType;
use App\Models\DevicesTypesBrandsProduct;
use Livewire\Component;

class AssoicateBrandAndDevice extends Component
{
    public $name, $selected_id;
    public $updateMode = false;
//    public $devices;

    public function render()
    {
        return view('livewire.products.assoicate-brand-and-device', ['devices' => DevicesTypesBrandsProduct::paginate(10)]);
    }

    public function mount()
    {
//        $this->devices = DevicesTypesBrandsProduct::paginate(10);
//        dd($this->devices);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        DeviceType::create([
            'name' => $this->name
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
