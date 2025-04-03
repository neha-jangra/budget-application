<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Sidebarmenu extends Component
{
    
    protected $listeners = ['refreshSidebarmenu' => '$refresh'];

    public function render()
    {
        return view('livewire.sidebarmenu');
    }
}
