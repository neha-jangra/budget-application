<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SweetAlert extends Component
{
    public $title;
    public $text;

    public function render()
    {
        return view('livewire.sweet-alert');
    }

    public function showAlert()
    {
        $this->dispatchBrowserEvent('show-sweetalert', [
            'title' => $this->title,
            'text' => $this->text,
        ]);
    }
}
