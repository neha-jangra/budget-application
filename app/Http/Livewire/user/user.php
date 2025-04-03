<?php

namespace App\Http\Livewire\user;

use Livewire\Component;

use Livewire\WithPagination;

use App\Repositories\UserRepository;

use App\Repositories\UserprofileRepository;

use App\Repositories\RoleRepository;

use App\Constants\RoleConstant;

use App\Http\Trait\ToastTrait;

class User extends Component
{
    use WithPagination, ToastTrait;

    protected $userRepository, $userprofileRepository, $roleRepository, $users = [];

    public $loader = true, $user_id;

    public $search_by_name, $recordsPerPage = 50;


    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {
        $this->userRepository            = app(UserRepository::class);

        $this->userprofileRepository     = app(UserprofileRepository::class);

        $this->roleRepository            = app(RoleRepository::class);
    }

    public function init()
    {
        usleep(50000);

        $this->loader = false;
    }

    public function mount()
    {
    }

    public function clearInput()
    {
        $this->search_by_name = '';
    }

    public function confirmDelete($id)
    {
        $this->user_id = $id;
    }

    public function redirectToeditpage($id)
    {
        return redirect()->route('user.edit', ['user' => $id]);
    }

    public function redirectTocreatepage()
    {
        return redirect()->to('/user/create');
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
            $this->toastMessage('Success!', 'User Deleted Successfully!', '', 'success');
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
        $this->setRepository();

        $this->users = $this->userRepository->with(['roles'])->whereHas(
            'roles',
            function ($roles) {
                $roles->whereNotIn('role_id', [RoleConstant::DONOR, RoleConstant::EMPLOYEE, RoleConstant::CONSULTANT, RoleConstant::SUBGRANTEE]);
            }
        )->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search_by_name . '%');
        })->orderBy('id', 'desc')->paginate($this->recordsPerPage);

        return view('livewire.user.user-list', ['users' => $this->users]);
    }
}
