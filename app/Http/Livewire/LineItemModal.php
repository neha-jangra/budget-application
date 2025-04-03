<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Repositories\RoleRepository;

use App\Repositories\UserRepository;

use App\Repositories\PermissionRepository;

use App\Repositories\UserprofileRepository;

use App\Constants\RoleConstant;

use Illuminate\Support\Facades\Route;

use Propaganistas\LaravelPhone\PhoneNumber;
use App\Models\{ExactEmployee};

class LineItemModal extends Component
{
    /** @var  RoleRepository */
    protected $roleRepository;

    /** @var  UserRepository */
    protected $userRepository;

    /** @var  PermissionRepository */
    protected $permissionRepository;

    /** @var  UserprofileRepository */
    protected $userprofileRepository;

    public $loader = false, $project_id, $users = [];

    public $user_type, $first_name, $exactEmployees=[], $last_name, $email, $phone_number, $company_name, $position, $rate, $country, $lastvalueSelect2, $sub_project_id, $collapse_id, $country_rate, $exact_name, $employee, $name;

    protected $listeners = ['updateUserType', 'updateCountry', 'updateSelect2', 'updatecountryRate', 'updateEmployee'];

    public function hydrate()
    {
        $this->setRepository();
    }

    // public function init()
    // {
    //     $this->loader = true;
    // }

    public function mount()
    {
        $this->project_id = Route::current()->parameter('project');
    }

    public function setRepository()
    {

        $this->roleRepository           = app(RoleRepository::class);

        $this->userRepository           = app(UserRepository::class);

        $this->permissionRepository     = app(PermissionRepository::class);

        $this->userprofileRepository    = app(UserprofileRepository::class);
    }

    public function updateUserType($value)
    {
        $this->user_type   = $value;

        $this->resetErrorBag('user_type');
    }

    public function updateCountry($value)
    {
        $this->country   = $value;
    }

    public function updatecountryRate($value)
    {
        $this->country_rate   = $value;
    }

    public function updateOldPhoneNumber()
    {
        $_hasPlusSign = hasPlusSign($this->phone_number);

        if ($_hasPlusSign) {
            $phoneNumber = new PhoneNumber($this->phone_number);

            $this->phone_number    = ltrim($phoneNumber->formatNational(), '0');
        } else {
            $this->phone_number    = ltrim($this->phone_number, '0');
        }
    }

    function updateSelect2($value, $sub_project_id = NULL, $collapse_id = NULL)
    {

        $this->lastvalueSelect2 = $value;

        $this->sub_project_id   = $sub_project_id;

        $this->collapse_id      = $collapse_id;
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {

        $this->user_type       = '';

        $this->first_name      = '';

        $this->last_name       = '';

        $this->email           = '';

        $this->phone_number    = '';

        $this->company_name    = '';

        $this->position        = '';

        $this->rate            = '';

        $this->country_rate    = '';
    }

    public function updated($name)
    {
        if ($this->user_type != 4) {
            $validatedData = $this->validateOnly($name, [

                'user_type'      => '',

                'email'          => 'required|unique:users,email',

                'phone_number'   => '',

                'rate'           => 'required|digits_between:1,500',

                'country_rate'   => '',


            ]);
        } else {
            $validatedData = $this->validateOnly($name, [

                'user_type'      => '',

                'email'          => 'required|unique:users,email',

                'phone_number'   => '',

                'country_rate'   => '',

            ]);
        }
    }

    /** create project user */
    public function createProjectUser()
    {
        if ($this->user_type == 3) {
            $validatedData = $this->validate([
                'employee' => 'required',

                'user_type'      => '',

                'email'          => 'required|unique:users,email',

                'phone_number'   => '',

                'country_rate'   => '',

            ], '');
        } else if ($this->user_type != 4) {
            $validatedData = $this->validate([

                'user_type'      => '',

                'first_name'     => 'required|max:25',

                'last_name'      => 'required|max:25',

                'email'          => 'nullable|unique:users,email',

                'phone_number'   => '',

                'rate'           => 'required|digits_between:1,500',

                'country_rate'   => '',

            ]);
        } else {
            $validatedData = $this->validate([

                'user_type'      => '',

                'first_name'     => 'required|max:25',

                'last_name'      => 'required|max:25',

                'email'          => 'nullable|unique:users,email',

                'phone_number'   => '',

                'country_rate'   => '',

            ]);
        }

        // Ensure empty email values are converted to null
        if (empty($validatedData['email'])) {
            $validatedData['email'] = null;
        }
        $nameData = getFirstNameLastName($this->exact_name);
        /** seprate the user table data */
        $_user_attribute  =  array(

            'exact_id' => $this->employee,
            'name' => $this->exact_name ??  $this->first_name .' '. $this->last_name,
            'email'          =>  $validatedData['email'],
            'phone_number'   =>  preg_replace('/[()\s-]/', '', $this->phone_number),
        );


        $user = $this->userRepository->create($_user_attribute);

        $role = $this->roleRepository->wherefirst(['id' => $this->user_type]);

        if ($role) {
            $user->roles()->attach($role);
        }

        $permission = $this->permissionRepository->wherefirst(['id' => RoleConstant::ADD_USER]);

        if ($permission) {
            // Assign permissions to roles
            if ($permission && $role && !$role->permissions()->where('name', $permission->name)->exists()) {
                $role->permissions()->attach([$permission->id]);
            }
        }
        

        /** seprate the userprofile table data */

        $_user_profile_attribute  =  array(
            'first_name' => $nameData['first_name'] ?? $this->first_name,
            'last_name' => $nameData['last_name'] ?? $this->last_name,
            'company'             =>   $this->company_name,

            'position'            =>   $this->position,

            'rate'                => ($this->rate) ? $this->rate : NULL,

            'user_id'             =>   $user->id,

            'country'             =>  $this->country,

            'country_rate'             =>  $this->country_rate,

        );

        $this->userprofileRepository->create($_user_profile_attribute);


        $this->emit('swal:alert', [

            'title'        => 'Success!',

            'text'         => $role->title . ' Created Successfully!',

            'icon'         => 'success',

            'redirectUrl'  => '',

            'status'       => 'success'
        ]);

        $this->emit('refreshSelect');
        $this->emit('close-modal-lineitem');
        $this->emit('reinitializeSelect2', $this->lastvalueSelect2, $this->sub_project_id, $this->collapse_id, $user->id);
        $this->resetInputFields();
    }


    public function render()
    {
        $this->exactEmployees = ExactEmployee::all();
        $this->setRepository();
        $this->users    = $this->roleRepository->where(function ($query) {
            $query->whereIn('id', [RoleConstant::EMPLOYEE, RoleConstant::CONSULTANT, RoleConstant::SUBGRANTEE])
                ->whereNotIn('id', [RoleConstant::ADMIN, RoleConstant::DONOR]);
        })->get();
        return view('livewire.line-item-modal');
    }

    public function updateEmployee($value)
    {
        $this->employee = $value;
        $this->name   = $value;
        $employee = ExactEmployee::where('exact_id', $value)->first();
        $this->exact_name =  $employee->full_name;
        $this->email =   $employee->email;
        $this->phone_number = $employee->mobile;
        $this->country = $employee->country;
        $this->rate = dutchCurrency($employee->rate);
        $this->emit('countryeditSelected', $this->country);
        $this->emit('loadCurrencyFormatter');
        $this->resetErrorBag('employee');
    }
}
