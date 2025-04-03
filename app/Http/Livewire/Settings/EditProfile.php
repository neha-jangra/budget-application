<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\{Auth, Storage};

class EditProfile extends Component
{
    use WithFileUploads;

    public $first_name;
    public $last_name;
    public $email;
    public $photo;
    public $user;
    public $photoDeleted = false; // Track photo deletion
    public $activeTab;

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'photo' => 'nullable|image',
        ];
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->first_name = $this->user->userprofile->first_name;
        $this->last_name = $this->user->userprofile->last_name;
        $this->email = $this->user->email;
         // Check if the user already has a photo
        if ($this->user->userprofile->photo) {
            $this->photoDeleted = false; // Reset the flag to ensure delete button is visible
        }
    }

    public function save()
    {
        $this->validate();
        $user = Auth::user();
        $user->email = $this->email;
        $user->name = $this->first_name . ' ' . $this->last_name;
        $user->save();
        $user->userprofile->first_name = $this->first_name;
        $user->userprofile->last_name = $this->last_name;
        if ($this->photo) {
            $path = $this->photo->store('photos', 'public');
            $user->userprofile->photo = $path;
            $this->photoDeleted = false; // Reset photoDeleted flag
        }
        if ($this->photoDeleted && $user->userprofile->photo) {
            Storage::disk('public')->delete('photos/' . $user->userprofile->photo);
            $user->userprofile->photo = null;
        }
        $user->userprofile->save();
        $this->emit('swal:alert', [
            'title' => 'Success!',
            'text' => 'Profile updated successfully!',
            'icon' => 'success',
            'redirectUrl' => '/settings',
            'status' => 'success'
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        // Reset $photoDeleted if photo property changes
        if ($propertyName === 'photo') {
            $this->photoDeleted = false;
        }
    }

    public function render()
    {
        $this->activeTab = 'edit_profile';
        return view('livewire.settings.edit-profile');
    }

    public function deletePhoto()
    {
        $this->photoDeleted = true; // Mark the photo as deleted
        $this->photo = null; // Reset the photo property
    }
}
