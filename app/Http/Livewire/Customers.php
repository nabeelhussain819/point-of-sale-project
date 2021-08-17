<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Carbon\Carbon;
use Livewire\Component;

class Customers extends Component
{

    public $name, $email, $telephone, $phone, $selected_id;
    public $updateMode = false;

    public function render()
    {

        return view('livewire.customer.index', ['customers' => Customer::orderBy("created_at", "desc")->paginate(25)]);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|unique:customers',
            'phone' => 'required',

        ]);

        Customer::insert([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $this->resetField();
        session()->flash('success', 'Customer Added');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->telephone = $customer->telephone;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $customer = Customer::findOrFail($this->selected_id);

        $customer->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->updateMode = false;
        session()->flash('success', 'Customer Updated');
    }

    public function delete($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        session()->flash('success', 'Customer Deleted');
    }

    public function resetField()
    {
        $this->name = null;
        $this->email = null;
        $this->phone = null;
    }
}
