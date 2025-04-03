<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Repositories\RoleRepository;

use App\Repositories\UserRepository;

use App\Repositories\PermissionRepository;

use App\Repositories\UserprofileRepository;

use App;

use App\Constants\RoleConstant;

use App\Models\Country;

use App\Models\{Role, ExactUsers};

use Propaganistas\LaravelPhone\PhoneNumber;

class Modal extends Component
{

    /** @var  RoleRepository */
    protected $roleRepository;

    /** @var  UserRepository */
    protected $userRepository;

    /** @var  PermissionRepository */
    protected $permissionRepository;

    /** @var  UserprofileRepository */
    protected $userprofileRepository;

    public $roles=[],$user_type,$company, $donor_name,$email,$donor_contact,$gender="male",$address,$city,$state,$pin_code,$country,$loader = true,$countries=[],$country_code, $exactDonors = [], $donor, $exact_name, $donorId;

    protected $listeners = ['updateUserType','updateCountry','updateCountryCode', 'updateDonorName'];

    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {

        $this->roleRepository           = App::make(RoleRepository::class);

        $this->userRepository           = App::make(UserRepository::class);

        $this->permissionRepository     = App::make(PermissionRepository::class);

        $this->userprofileRepository    = App::make(UserprofileRepository::class);
        
    }

    public function mount()
    {
        $this->getRoles();
    }

    public function getRoles()
    {
        
        $this->roles  = Role::whereNotIn('id',[1,3,4,5])->get();

        $this->loader = false;
    }

    // public function updateGender($value)
    // {
    //     $this->gender   = $value;
    // }


    public function render()
    {
        $this->countries = Country::all();
        $this->exactDonors = ExactUsers::all();

        return view('livewire.modal');
    }

    public function updateUserType($value)
    {
        $this->user_type   = RoleConstant::DONOR;
    }

    public function updateCountry($value)
    {
        $this->country   = $value;
    }

    public function updateCountryCode($value)
    {
        $this->country_code   = $value;

        $this->resetErrorBag('country_code');
    }

    public function updateOldPhoneNumber()
    {
        $_hasPlusSign = hasPlusSign($this->donor_contact);

        if($_hasPlusSign)
        {
            $phoneNumber = new PhoneNumber($this->donor_contact);
            
            $this->donor_contact    = ltrim($phoneNumber->formatNational(),'0');
        }
        else
        {
            $this->donor_contact    = ltrim($this->donor_contact,'0');
        }
        
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {

        $this->user_type      = '';

        $this->company        = '';

        $this->donor_name     = '';

        $this->email          = '';

        $this->donor_contact  = '';

        $this->address        = '';

        $this->city           = '';

        $this->state          = '';

        $this->pin_code       = '';

        $this->country        = '';

    }

    public function updated($name)
    {
        $this->validateOnly($name, [

            'company'        => '',

            'donor_name'           => 'required',

            'email'          => 'required|unique:users,email',

            'donor_contact'  => '',

            'gender'         => '',

            'address'        => '',

            'city'           => '',

            'state'          => '',

            'pin_code'       => '',

            'country'        => '',

            'country_code'   => '',
        ], [
            'donor_name.required' => 'Please select a donor.',
        ]);
    }

    /** create user */
    public function createDonor()
    {
       
        $validatedData = $this->validate([

            'user_type'      => 'required',

            'company'        => '',

            'donor_name'           => 'required',

            'email'          => 'required|unique:users,email',

            'donor_contact'  => '',

            'gender'         => '',

            'address'        => '',

            'city'           => '',

            'state'          => '',

            'pin_code'       => '',

            'country'        => '',

            'country_code'   => '',

        ], [
            'donor_name.required' => 'Please select a donor.',
        ]);
        
        /** seprate the user table data */

        $_user_attribute  =  array(
            'name'           =>  $this->exact_name,

            'exact_id'           =>  $this->donorId,

            'email'          =>  $validatedData['email'],

            'phone_number'   =>  preg_replace('/[()\s-]/', '', $this->donor_contact),
        );
        
        $user = $this->userRepository->create($_user_attribute);

        $role = $this->roleRepository->wherefirst(['id' => $validatedData['user_type']]);

        if ($role)
        {
            $user->roles()->attach($role);
        }

        $permission = $this->permissionRepository->wherefirst(['id' => RoleConstant::ADD_USER]);

        if($permission)
        {
            // Assign permissions to roles
            if ($permission && $role && !$role->permissions()->where('name', $permission->name)->exists())
            {
                $role->permissions()->attach([$permission->id]);
            }
        }

        /** seprate the userprofile table data */

        $_user_profile_attribute  =  array(

            'company'             =>  $this->company,

            'address'             =>  $this->address,

            'city'                =>  $this->city,

            'state'               =>  $this->state,

            'country'             =>  $this->country,

            'pin_code'            =>  $this->pin_code,

            'gender'              =>  $this->gender,

            'user_id'             =>  $user->id,

            'country_code'        => $this->country_code
        );

        $this->userprofileRepository->create($_user_profile_attribute);

        $this->emit('swal:alert', [

            'title' => 'Success!',

            'text' => 'Donor Created Successfully!',

            'icon' => 'success',

            'redirectUrl' => '',

            'status'  => 'success'
        ]);

        $this->emit('refreshSelect2');

        $this->emit('close-modal');

        $this->resetInputFields();
    }

    public function updateDonorName($value)
    {
        $this->donorId   = $value;
        $this->donor   = $value;
        $donor = ExactUsers::where('account_id', $value)->first();
        $this->exact_name =  $donor->account_name;
        $this->donor_name =  $donor->account_name;
        $this->email =   $donor->email;
        $this->country_code = $donor->country;
        $this->donor_contact = $donor->phone;
        $this->city = $donor->city;
        $this->state = $donor->state;
        $this->emit('countryeditSelected', $this->country_code);
        $this->resetErrorBag('donor_name');
    }
}
