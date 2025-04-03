<?php

namespace App\Http\Livewire\rolemanagement;

use Livewire\Component;

use Livewire\WithPagination;

use App\Http\Trait\ToastTrait;

use App\Repositories\RoleRepository;

use App\Repositories\PermissionRepository;

use App\Constants\RoleConstant;

use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class Rolemanagement extends Component
{
    use WithPagination,ToastTrait;

    protected $roleRepository,$permissionRepository,$roles=[],$permissions,$role_permission;

    public $role,$loader= true,$search_by_name,$recordsPerPage = 10,$permission_data =[],$editrole=NULL,$role_id,$role_name,$activeTab = 'tab1',$editing = false;

    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {
        $this->roleRepository            = app()->make(RoleRepository::class);

        $this->permissionRepository      = app()->make(PermissionRepository::class);
    }

    public function mount(Request $request) 
    {
        $this->setRepository();

        $role_permission = $this->roleRepository->all();

        $permissions     =  $this->permissionRepository->all();

        foreach ($role_permission as $role) 
        {
            foreach ($permissions as $permission) 
            {
                $this->permission_data[$role->id][$permission->id] = $role->hasPermission($permission->name);
            }
        }

        $activeTabFromQuery = $request->query('active_tab');

        if ($activeTabFromQuery && in_array($activeTabFromQuery, ['tab1', 'tab2'])) 
        {
            $this->activeTab = $activeTabFromQuery;
        }
    }

    public function redirectPermission($tab)
    {
        $this->activeTab = $tab;
    
        $this->loader = true;

        $this->activeTab = $tab;

        $this->loader = false;
    }

    public function getRoles()
    {  
        usleep(50000);
         
        $this->loader = false;
    }

    public function editRole($id)
    {
        $role           = $this->roleRepository->wherefirst(['id' => $id]);

        $this->role     = $role->title;

        $this->role_id  = $role->id;
    }

    public function clearInput()
    {
        $this->search_by_name = '';
    }

    public function roleStore()
    {
        $validatedData = $this->validate([

            'role_name'     => 'required|unique:roles,title'
        ]);

        

        $_role_attribute  =  array(

            'title'                =>  $this->role_name,

            'name'                 =>  $this->role_name,

            'guard_name'           =>  'web',

        );

        $this->roleRepository->create($_role_attribute);

        $this->resetInputFields();

        $this->emit('close-modal-role','data');

        $this->toastMessage('Success!','Role Created Successfully!','','success');
    }

    public function updated($name)
    {
        $this->validateOnly($name, [

            'role_name'     => 'required|unique:roles,title'
        ]);
    }

    public function savePermissions()
    {
        $_permission_data =  $this->permission_data;

        foreach ($this->permission_data as $role_id => $permissions) 
        {
            foreach ($permissions as $permission_id => $isChecked) 
            {
                if($isChecked)
                {
                    $role         =  $this->roleRepository->wherefirst(['id' => $role_id]);

                    $permission   = $this->permissionRepository->wherefirst(['id' => $permission_id]);

                    if($permission)
                    {
                        // Assign permissions to roles
                        if ($permission && $role && !$role->permissions()->where('name', $permission->name)->exists()) 
                        {
                            $role->permissions()->detach($permission->id);

                            $role->permissions()->attach([$permission->id]);
                        }
                    }
                }else
                {
                    $role         =  $this->roleRepository->wherefirst(['id' => $role_id]);

                    $permission   = $this->permissionRepository->wherefirst(['id' => $permission_id]);
                    
                    if($permission && $role && $role->permissions()->where('name', $permission->name)->exists()) 
                    {
                        
                        $role->permissions()->detach($permission->id);
                        
                    }
                }
            }
        }

        $this->toastMessage('Success!','Permission updated Successfully!','','success');

        $this->emit('refreshSidebarmenu');

        $this->editing = false;
    }

    public function roleEdit()
    {
        $validatedData = $this->validate([

            'role'     => ['required',Rule::unique('roles','title')->ignore($this->role_id)],
            
        ]);

        $_role_attribute  =  array(

            'title'                =>  $this->role,

        );

        $this->roleRepository->whereUpdate(['id' => $this->role_id],$_role_attribute);

        $this->emit('commonModal','edit_role');

        $this->toastMessage('Success!','Role Edited Successfully!','','success');
    }

    public function confirmDelete($id)
    {
        $this->role_id = $id;
    }

    public function delete()
    {
        try 
        {
            $role_id = $this->role_id;

            if($this->role_id == 1)
            {
                $this->emit('commonModal','delete_role');

                $this->toastMessage('Error!','Admin role can not be deleted.','','error');

                return false;

            }else
            {
                $roleRelation = $this->roleRepository->whereHas('roleuser', function ($query) use ($role_id) {
                    $query->where('role_id', $role_id);
                })->exists();
                
                if($roleRelation)
                {
                    $this->emit('commonModal','delete_role');

                    $this->toastMessage('Error!','The role can not be deleted as this role is currently assigned to the user.','','error');

                    return false;
                }
            }
            
            $this->roleRepository->Wheredelete(['id' => $this->role_id]);

            $this->emit('commonModal','delete_role');

            $this->toastMessage('Success!','Role Deleted Successfully!','','success');

        } catch (\Exception $e) 
        {

            $this->toastMessage('Error!',$e->getMessage(),'','error');
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {
        $this->role_name = '';
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function cancel(Request $request)
    {
        $this->editing = false;

        $this->mount($request);
    }

    public function render()
    {
        $this->setRepository();

        $this->roles              = $this->roleRepository->where(function ($query) {
            $query->whereNotIn('id', [
                RoleConstant::DONOR,
                RoleConstant::EMPLOYEE,
                RoleConstant::CONSULTANT,
                RoleConstant::SUBGRANTEE
            ])->where('title', 'like', '%' . $this->search_by_name . '%');

        })->paginate($this->recordsPerPage);  

        $this->role_permission     = $this->roleRepository->where(function ($query) {
            $query->whereNotIn('id', [
                RoleConstant::DONOR,
                RoleConstant::EMPLOYEE,
                RoleConstant::CONSULTANT,
                RoleConstant::SUBGRANTEE
            ]);

        })->get();  
        
        $this->permissions        = $this->permissionRepository->all();

        return view('livewire.rolemanagement.rolemanagement',['roles' => $this->roles,'permissions' => $this->permissions,'role_permission' => $this->role_permission,'activeTab' => $this->activeTab]);
    }
}
