<?php

namespace App\Http\Livewire\user;

use Livewire\Component;

use App\Repositories\RoleRepository;

use App\Repositories\UserRepository;

use App\Repositories\UserprofileRepository;

use App\Models\User;

use Illuminate\Support\Facades\Route;

use App\Constants\RoleConstant;

use Illuminate\Validation\Rule;

use App\Http\Trait\ToastTrait;

class Edit extends Component
{
    use ToastTrait;

    /** @var  RoleRepository */
    protected $roleRepository;

    /** @var  UserRepository */
    protected $userRepository;

    /** @var  UserprofileRepository */
    protected $userprofileRepository;

    public $load = false;

    public $first_name, $last_name, $email, $role, $roles = [], $message, $user_id, $user;

    protected $listeners = ['updateRole'];

    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {

        $this->roleRepository           = app()->make(RoleRepository::class);

        $this->userRepository           = app()->make(UserRepository::class);

        $this->userprofileRepository    = app()->make(UserprofileRepository::class);
    }

    public function mount()
    {

        $this->setRepository();

        $this->roles         = $this->roleRepository->whereNotIn('id', [RoleConstant::DONOR, RoleConstant::EMPLOYEE, RoleConstant::CONSULTANT, RoleConstant::SUBGRANTEE]);

        $this->user_id       = Route::current()->parameter('user');;

        $this->user          = User::where(['id' => $this->user_id])->first();

        $this->first_name    = isset($this->user->userprofile->first_name) ? $this->user->userprofile->first_name : '';

        $this->last_name     = isset($this->user->userprofile->last_name)  ? $this->user->userprofile->last_name  : '';

        $this->email         = isset($this->user->email)                   ? $this->user->email                   : '';
        $rolesToKeep = ['Employee', 'Donor', 'Sub-grantee', 'Consultant'];
        foreach ($this->user->roles as $role) {
            if (!in_array($role->title, $rolesToKeep)) {
                $this->role = $role->id;
            }
        }
    }

    public function updateRole($value)
    {
        $this->role   = $value;

        $this->resetErrorBag('role');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {

        $this->first_name            = '';

        $this->last_name             = '';

        $this->email                 = '';

        $this->role                  = '';
    }

    /** edit project user */
    public function edit()
    {

        $validatedData = $this->validate([

            'first_name'            => 'required|max:100',

            'last_name'             => 'required|max:100',

            'email'                 => ['required', Rule::unique('users')->ignore($this->user_id)],

            'role'                  => 'required'

        ]);

        $this->load = true;

        $user = $this->userRepository->wherefirst(['id' => $this->user_id]);

        $_user_attribute  =  array(

            'name'           =>  $this->first_name . ' ' . $this->last_name,

            'email'          =>  $validatedData['email'],
        );

        $this->userRepository->whereUpdate(['id' => $this->user_id], $_user_attribute);

        /** update the userprofile table data */

        $_user_profile_attribute  =  array(

            'first_name'          =>   $this->first_name,

            'last_name'          =>    $this->last_name,
        );

        $this->userprofileRepository->whereUpdate(['user_id' => $this->user_id], $_user_profile_attribute);
        $role = $this->roleRepository->wherefirst(['id' => $this->role]);
        if ($role) {
            $rolesToKeep = ['Employee', 'Donor', 'Sub-grantee', 'Consultant'];
            foreach ($user->roles as $roleValue) {
                if (!in_array($roleValue->title, $rolesToKeep)) {
                    $user->roles()->detach($roleValue->pivot->role_id);
                }
            }
        }
        $user->roles()->attach($role);
        $this->load = false;
        $this->toastMessage('Success!', 'User Edited Successfully!', '/user', 'success');
    }

    /** update donor validation */
    public function updated($name)
    {
        $this->validateOnly($name, [

            'first_name'            => 'required|max:100',

            'last_name'             => 'required|max:100',

            'email'                 => ['required', Rule::unique('users')->ignore($this->user_id)],

            'role'                  => 'required'

        ]);
    }

    public function confirmDelete($id)
    {
        $this->user_id = $id;
    }

    /** delete user */
    public function delete()
    {
        try {
            if ($this->user_id == 1) {
                $this->emit('commonModal', 'delete_user');

                $this->toastMessage('Error!', 'The Admin can not be deleted.', '', 'error');

                return false;
            }

            $user = $this->userRepository->wherefirst(['id' => $this->user_id]);
            $rolesToKeep = ['Employee', 'Donor', 'Sub-grantee', 'Consultant'];
            foreach ($user->roles as $role) {
                if (!in_array($role->title, $rolesToKeep)) {
                    $user->roles()->detach($role->pivot->role_id);
                }
            }
            if (count($user->roles()->get()) == 0) {
                $this->userprofileRepository->Wheredelete(['user_id' => $this->user_id]);
                $this->userRepository->Wheredelete(['id' => $this->user_id]);
            }

            $this->emit('commonModal', 'delete_user');

            $this->toastMessage('Success!', 'User Deleted Successfully!', '/user', 'success');
        } catch (\Exception $e) {

            $this->emit('swal:alert', [

                'title' => 'Error!',

                'text' => $e->getMessage(),

                'icon' => 'error',

                'status'  => 'error'
            ]);
        }
    }


    public function render()
    {
        return view('livewire.user.user-edit');
    }
}
