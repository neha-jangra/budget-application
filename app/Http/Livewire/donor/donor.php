<?php

namespace App\Http\Livewire\donor;

use Livewire\Component;

use Livewire\WithPagination;

use App\Repositories\UserRepository;

use App\Repositories\UserprofileRepository;

use App\Repositories\RoleRepository;

use App\Repositories\ProjectRepository;

use App\Constants\RoleConstant;

use App\Http\Trait\ToastTrait;

class Donor extends Component
{
    use WithPagination,ToastTrait;

    protected $userRepository,$userprofileRepository,$roleRepository,$users = [],$projectRepository;

    public $loader = true,$donor_id ;

    public $search_by_name,$recordsPerPage = 10;
    
    
    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {
        $this->userRepository                       = app(UserRepository::class);

        $this->userprofileRepository                = app(UserprofileRepository::class);

        $this->roleRepository                       = app(RoleRepository::class);

        $this->roleRepository                       = app(RoleRepository::class);

        $this->projectRepository                    = app(ProjectRepository::class);
    }

    public function init()
    {
        usleep(50000);

        $this->loader = false;
    }

    public function clearInput()
    {
        $this->search_by_name = '';
    }

    public function confirmDelete($id)
    {
        $this->donor_id = $id;
    }

    public function delete()
    {
        try 
        {
            $project = $this->projectRepository->wherefirst(['project_donor_id' => $this->donor_id]);
            
            if($project)
            {
                
                $this->emit('commonModal','delete_donor');

                $this->toastMessage('Error!','Donor can not be deleted, as it is already allocated on a project.','','error');

                return false;
            }
            
            $user = $this->userRepository->wherefirst(['id' => $this->donor_id]);

            $this->userprofileRepository->Wheredelete(['user_id' => $this->donor_id]);

            $role = $this->roleRepository->wherefirst(['id' => RoleConstant::DONOR]);

            $user->roles()->detach($role);

            $this->userRepository->Wheredelete(['id' => $this->donor_id]);

            $this->emit('commonModal','delete_donor');
            
            $this->toastMessage('Success!','Donor Deleted Successfully!','','success');

        } catch (\Exception $e) 
        {
    
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

        $this->users = $this->userRepository->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::DONOR);
            }
        )->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search_by_name . '%');
               
        })->orderBy('id', 'desc')->paginate($this->recordsPerPage);

        $donorDataExists = $this->userRepository->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::DONOR);
            }
        )->count() > 0;
        
        return view('livewire.donor.donor',['users' => $this->users,'donorDataExists' =>$donorDataExists]);
    }
}
