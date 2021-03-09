<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Reconciliation extends Component
{
    public function render()
    {
        return view('livewire.reconciliation.reconciliation');
    }

    public function submit()
    {
        $this->validate();

        // Execution doesn't reach here if validation fails.

        Contact::create([
            'name' => $this->name,
            'email' => $this->email,
        ]);
    }
}
