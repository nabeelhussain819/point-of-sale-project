<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Test extends Component
{
    use WithPagination;

    public $name,$fullName,$reference,$status, $selected_id;
    public $updateMode = false;

    public function render()
    {
        return view('livewire.category.index',['categories' => Category::paginate(8)]);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'status' => 'required',
            'reference' => 'required'
        ]);

        Category::insert([
            'name' => $this->name,
            'full_name' => $this->fullName,
            'reference' => $this->reference,
            'active' => $this->status,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $this->resetFields();
        session()->flash('success','Category Added');
    }

    public function edit($id)
    {
        $department = \App\Models\Category::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $department->name;
        $this->status = $department->active;
        $this->fullName = $department->full_name;
        $this->reference = $department->reference;
        $this->updateMode = true;

    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required',
            'status' => 'required',
            'reference' => 'required'
        ]);
        $category = Category::findOrFail($this->selected_id);
        $category->update([
            'name' => $this->name,
            'full_name' => $this->fullName,
            'reference' => $this->reference,
            'active' => $this->status,
        ]);
        $this->updateMode = false;
        $this->resetFields();
        session()->flash('success','Category Updated');
    }

    public function resetFields()
    {
        $this->name = null;
        $this->fullName = null;
        $this->reference = null;
        $this->selectedValue = null;
    }

    public function delete($id)
    {
       $category = Category::find($id);
       $category->delete();
       session()->flash('success','Category Deleted');
    }
}
