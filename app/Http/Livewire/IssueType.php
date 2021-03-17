<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\IssueType as IssueTypeModel;

class IssueType extends Component
{

    public $name, $selected_id;
    public $updateMode = false;

    public function render()
    {
        return view('livewire.issue-type', ['devicesType' => IssueTypeModel::all()]);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        IssueTypeModel::create([
            'name' => $this->name
        ]);
        $this->resetField();
        session()->flash('success', 'Added');
    }

    public function resetField()
    {
        $this->name = null;
    }

    public function edit(IssueTypeModel $devicesType)
    {
        $this->selected_id = $devicesType->id;
        $this->name = $devicesType->name;
        $this->updateMode = true;
    }

    public function update(IssueTypeModel $devicesType)
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
     * @param IssueTypeModel $devicesType
     * @throws \Exception
     */
    public function delete(IssueTypeModel $devicesType)
    {
        $devicesType->delete();
        session()->flash('success', 'Customer Deleted');
    }
}
