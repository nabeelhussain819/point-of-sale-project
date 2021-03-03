<?php

namespace App\Http\Livewire;

use App\Models\StockBin;
use Carbon\Carbon;
use Livewire\Component;

class Stock extends Component
{
    public $name, $selected_id;
    public $updateMode = false;

    public function render()
    {
        return view('livewire.stocks.index', ['stocks' => \App\Models\StockBin::all()]);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        \App\Models\StockBin::insert([
            'name' => $this->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $this->resetFields();
        session()->flash('success', 'StockBin Added');
    }

    public function edit($id)
    {
        $stock = \App\Models\StockBin::findOrFail($id);
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
        $category = \App\Models\StockBin::findOrFail($this->selected_id);
        $category->update([
            'name' => $this->name,
        ]);
        $this->updateMode = false;
        $this->resetFields();
        session()->flash('success', 'StockBin Updated');
    }

    public function resetFields()
    {
        $this->name = null;
    }

    /**
     * @param StockBin $stockBin
     * @throws \Exception
     */
    public function delete(StockBin $stockBin)
    {
        if ($stockBin->system) {
            return $this->addError('error', 'System field  not allowed to delete');
        }

        $stockBin->delete();
        session()->flash('success', 'StockBin Deleted');
    }
}
