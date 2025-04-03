<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePassword extends Component
{
    public $current_password;
    public $new_password;
    public $confirm_password;
    public $activeTab;

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|min:8',
        'confirm_password' => 'required|same:new_password',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();
        $user = Auth::user();
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password does not match our records.');
            return;
        }
        $user->password = Hash::make($this->new_password);
        $user->save();
        
        session()->flash('message', 'Password changed successfully!');

        $this->emit('swal:alert', [
            'title' => 'Success!',
            'text' => 'Password changed successfully!',
            'icon' => 'success',
            'redirectUrl' => '/settings?active_tab=password',
            'status' => 'success'
        ]);

        $this->reset(); // Reset form fields after successful save
    }

    public function render()
    {
        $this->activeTab='password';
        return view('livewire.settings.change-password');
    }
}
