<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Brand as BrandModel;

class Brand extends Component
{

    public $name, $selected_id;
    public $updateMode = false;

    public function render()
    {
        return view('livewire.brand.index', ['devicesType' => BrandModel::all()]);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        BrandModel::create([
            'name' => $this->name
        ]);
        $this->resetField();
        session()->flash('success', 'Added');
    }

    public function resetField()
    {
        $this->name = null;
    }

    public function edit(BrandModel $devicesType)
    {
        $this->selected_id = $devicesType->id;
        $this->name = $devicesType->name;
        $this->updateMode = true;
    }

    public function update(BrandModel $devicesType)
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
     * @param BrandModel $devicesType
     * @throws \Exception
     */
    public function delete(BrandModel $devicesType)
    {
        $devicesType->delete();
        session()->flash('success', 'Customer Deleted');
    }
}
