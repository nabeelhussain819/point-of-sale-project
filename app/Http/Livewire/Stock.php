<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class Stock extends Component
{
    public $name, $selected_id;
    public $updateMode = false;

    public function render()
    {
        return view('livewire.stocks.index', ['stocks' => \App\Models\Stock::all()]);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        \App\Models\Stock::insert([
            'name' => $this->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $this->resetFields();
        session()->flash('success', 'Stock Added');
    }

    public function edit($id)
    {
        $stock = \App\Models\Stock::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $stock->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required',
        ]);
        $category = \App\Models\Stock::findOrFail($this->selected_id);
        $category->update([
            'name' => $this->name,
        ]);
        $this->updateMode = false;
        $this->resetFields();
        session()->flash('success', 'Stock Updated');
    }

    public function resetFields()
    {
        $this->name = null;
    }

    public function delete($id)
    {
        $stock = \App\Models\Stock::find($id);
        $stock->delete();
        session()->flash('success', 'Stock Deleted');
    }
}
