<?php

namespace App\Http\Livewire;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Department extends Component
{
    use WithPagination;

    public $name, $fullName, $reference, $status, $selected_id;
    public $updateMode = false;

    public function render()
    {
        return view('livewire.department.index',['departments' =>\App\Models\Department::paginate(8) ]);
    }

    public function resetFields()
    {
        $this->name = null;
        $this->fullName = null;
        $this->reference = null;
        $this->status = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'status' => 'required',
            'reference' => 'required'
        ]);

        \App\Models\Department::insert([
            'name' => $this->name,
            'full_name' => $this->fullName,
            'reference' => $this->reference,
            'active' => $this->status,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $this->resetFields();
        session()->flash('success','Department Added');
    }

    public function edit($id)
    {
        $department = \App\Models\Department::findOrFail($id);
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

        $department = \App\Models\Department::findOrFail($this->selected_id);
        $department->update([
            'name' => $this->name,
            'full_name' => $this->fullName,
            'reference' => $this->reference,
            'active' => $this->status,
        ]);
        $this->updateMode = false;
        $this->resetFields();
        session()->flash('success','Department Updated');
    }

    public function delete($id)
    {
        $department = \App\Models\Department::findOrFail($id);
        $department->delete();
        session()->flash('success', 'Department Deleted');
    }
}
