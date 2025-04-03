<?php

namespace App\Http\Livewire\donor;

use Livewire\Component;

use App\Repositories\RoleRepository;

use App\Repositories\UserRepository;

use App\Repositories\PermissionRepository;

use App\Repositories\UserprofileRepository;

use App\Constants\RoleConstant;

use Illuminate\Support\Facades\Route;

use App\Models\{Country, ExactUsers};

use App\Http\Trait\ToastTrait;

use Propaganistas\LaravelPhone\PhoneNumber;

class Create extends Component
{
    use ToastTrait;

    /** @var  RoleRepository */
    protected $roleRepository;

    /** @var  UserRepository */
    protected $userRepository;

    /** @var  PermissionRepository */
    protected $permissionRepository;

    /** @var  UserprofileRepository */
    protected $userprofileRepository;

    public $loader = false, $project_id, $users = [];

    public $user_type, $donor, $exact_name, $email, $phone_number, $company_name, $gender = 'male', $address, $city, $state, $pin_code, $project_code, $country, $oldPhoneNumber, $countries, $country_code, $exactDonors=[], $donorId, $name;

    protected $listeners = ['updateCountry', 'updateCountryCode', 'updateName', 'updateData'];

    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {

        $this->roleRepository           = app(RoleRepository::class);

        $this->userRepository           = app(UserRepository::class);

        $this->permissionRepository     = app(PermissionRepository::class);

        $this->userprofileRepository    = app(UserprofileRepository::class);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {

        $this->donor            = '';

        $this->email           = '';

        $this->phone_number    = '';

        $this->company_name    = '';

        $this->gender          = '';

        $this->address         = '';

        $this->city            = '';

        $this->state           = '';

        $this->pin_code        = '';

        $this->project_code        = '';

        $this->country         = '';
    }

    public function getDonors(){
        $this->exactDonors = ExactUsers::all();
    }

    public function updateOldPhoneNumber()
    {
        $this->phone_number = removeZeroCountry($this->phone_number);
    }


    public function updateCountry($value)
    {
        $this->country   = $value;
    }

    public function updateName($value)
    {
        $this->donorId   = $value;
        $this->donor   = $value;
        $donor = ExactUsers::where('account_id', $value)->first();
        $this->exact_name =  $donor->account_name;
        $this->name =  $donor->account_name;
        $this->email =  $donor->email;
        $this->country_code = $donor->country;
        $this->phone_number = $donor->phone;
        $this->city = $donor->city;
        $this->state = $donor->state;
        $this->emit('countryeditSelected', $this->country_code);
        $this->resetErrorBag('name');
    }


    public function updateGender($value)
    {
        $this->gender = $value;
    }

    public function updateCountryCode($value)
    {
        $this->country_code   = $value;

        $this->resetErrorBag('country_code');
    }

    public function updateData($value)
    {
        
    }

    /** create project user */
    public function store()
    {
        $validatedData = $this->validate([
            'name'           => 'required',
            'email'          => 'required|unique:users,email',
            'phone_number'   => '',
            'gender'         =>  '',
            'city'           =>  '',
            'state'          =>  '',
            'pin_code'       =>  '',
            'project_code'   =>  '',
            'country_code'   => ''
        ]);

        /** seprate the user table data */

        $_user_attribute  =  array(

            'name'           =>  $this->exact_name,

            'exact_id'           =>  $this->donorId,

            'email'          =>  $validatedData['email'],

            'phone_number'   =>  preg_replace('/[()\s-]/', '', $this->phone_number),
        );

        $user = $this->userRepository->create($_user_attribute);

        $role = $this->roleRepository->wherefirst(['id' => RoleConstant::DONOR]);

        if ($role) {
            $user->roles()->attach($role);
        }

        /** seprate the userprofile table data */

        $nameData = getFirstNameLastName($this->exact_name);

        $_user_profile_attribute  =  array(

            'first_name'          =>   $nameData['first_name'],

            'last_name'          =>    $nameData['last_name'],

            'company'             =>   $this->company_name,

            'gender'              =>   $this->gender,

            'address'             =>   $this->address,

            'city'                =>   $this->city,

            'state'               =>   $this->state,

            'pin_code'            =>   $this->pin_code,

            'project_code'        =>   $this->project_code,

            'country'             =>   $this->country,

            'user_id'             =>   $user->id,

            'country_code'        =>   $this->country_code,
        );

        $this->userprofileRepository->create($_user_profile_attribute);

        $this->emit('swal:alert:donor', [

            'title'         => 'Success!',

            'text'          => 'Donor Created Successfully!',

            'icon'          => 'success',

            'redirectUrl'   => '/donor',

            'status'        => 'success'
        ]);
    }

    /** update donor validation */
    public function updated($name)
    {
        $this->validateOnly($name, [

            'name'           => 'required',

            'email'          => 'required|unique:users,email',

            'phone_number'   => '',

            'gender'         =>  '',

            'city'           =>  '',

            'state'          =>  '',

            'pin_code'       =>  '',

            'project_code'       =>  ''

        ]);
    }

    public function render()
    {
        $this->countries = Country::all();
        $this->getDonors();

        return view('livewire.donor.create');
    }
}
