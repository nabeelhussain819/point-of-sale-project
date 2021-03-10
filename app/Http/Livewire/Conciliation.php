<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Conciliation extends Component
{
    public $conciliation, $inventories, $concileData, $data = 1;

    public function render()
    {
        return view('livewire.reconciliation.conciliation');
    }

    public function mount($conciliation, $inventories, $concileData)
    {
        $this->concileData = $conciliation;
        $this->inventories = $inventories;
        $this->concileData = $concileData;
    }

    public function tab($tab)
    {
//        switch ($tab) {
//
//            case 1: //overStock
//                $this->data = 1;
//
//                break;
//            case 2: //Deficit
//                $this->data = 2;
//                break;
//            case 3: //Matched
//                $this->data = 3;
//                break;
//        }


    }
}
