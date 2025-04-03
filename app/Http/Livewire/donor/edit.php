<?php

namespace App\Http\Livewire\donor;

use Livewire\Component;

use App\Repositories\RoleRepository;

use App\Repositories\UserRepository;

use App\Repositories\PermissionRepository;

use App\Repositories\UserprofileRepository;

use App\Repositories\ProjectRepository;

use App\Models\{Country, ExactUsers};

use Illuminate\Support\Facades\Route;

use Illuminate\Validation\Rule;

use App\Constants\RoleConstant;

use App\Http\Trait\ToastTrait;

use Propaganistas\LaravelPhone\PhoneNumber;

class Edit extends Component
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

    /** @var  ProjectRepository */
    protected $projectRepository;

    public $loader = false, $project_id, $users = [];

    public $user_type, $name, $exact_name, $email, $phone_number, $company_name, $gender, $address, $city, $state, $pin_code, $project_code, $country, $donor_id, $countries, $country_code, $user_country, $countrys, $exactDonors=[], $donorId;

    protected $listeners = ['updateCountry', 'updateCountryCode', 'updatedProjectDonorId', 'updateName'];

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
        $this->setRepository();

        $this->donor_id                           = Route::current()->parameter('donor');

        $this->users                              = $this->userRepository->wherefirst(['id' => $this->donor_id]);

        $this->countries                          = Country::all();


        //pass the data to the parameters

        $this->name                      = $this->users->exact_id;

        $this->donorId                   = $this->users->exact_id;

        $this->exact_name                = $this->users->name;

        $this->email                     = $this->users->email;

        $this->phone_number              = $this->users->phone_number;

        $this->company_name              = isset($this->users->userprofile->company) ? $this->users->userprofile->company : '';

        $this->gender                    = isset($this->users->userprofile->gender) ? $this->users->userprofile->gender : '';

        $this->address                   = isset($this->users->userprofile->address) ? $this->users->userprofile->address : '';

        $this->city                      = isset($this->users->userprofile->city) ? $this->users->userprofile->city : '';

        $this->state                     = isset($this->users->userprofile->state) ? $this->users->userprofile->state : '';

        $this->pin_code                  = isset($this->users->userprofile->pin_code) ? $this->users->userprofile->pin_code : '';

        $this->project_code                  = isset($this->users->userprofile->project_code) ? $this->users->userprofile->project_code : '';

        $this->country                   = isset($this->users->userprofile->country) ? $this->users->userprofile->country : '';

        $this->country_code              = isset($this->users->userprofile->country_code) ? $this->users->userprofile->country_code : '';

        $this->getDonors();

        $this->emit('countryeditSelected', $this->user_country);
    }

    public function setRepository()
    {

        $this->roleRepository           = app(RoleRepository::class);

        $this->userRepository           = app(UserRepository::class);

        $this->permissionRepository     = app(PermissionRepository::class);

        $this->userprofileRepository    = app(UserprofileRepository::class);

        $this->projectRepository        = app(ProjectRepository::class);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {

        $this->name            = '';

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

    public function updateName($value)
    {
        $this->donorId   = $value;
        $this->name   = $value;
        $donor = ExactUsers::where('account_id', $value)->first();
        $this->exact_name =  $donor->account_name;
        $this->email =   $donor->email;
        $this->country_code = $donor->country;
        $this->phone_number = $donor->phone;
        $this->address = $donor->address;
        $this->city = $donor->city;
        $this->state = $donor->state;
        $this->resetErrorBag('name');
        $this->emit('countryeditSelected', $this->country_code);
    }


    public function updateOldPhoneNumber()
    {
        $this->phone_number = removeZeroCountry($this->phone_number);
    }

    public function updateCountry($value)
    {
        $this->countrys   = $value;
    }

    public function updateCountryCode($value)
    {
        $this->country_code   = $value;

        $this->resetErrorBag('country_code');
    }

    public function updatedProjectDonorId($value)
    {
        $selectedDonor                = $this->userRepository->wherefirst(['id' => $value]);

        if ($selectedDonor) {

            $this->user_country       = isset($selectedDonor->userprofile->country) ? $selectedDonor->userprofile->country : '';

            $this->emit('countryeditSelected', $this->user_country);
        }
    }

    /** edit donor */
    public function edit()
    {

        $validatedData = $this->validate([

            'name'           => 'required|max:100',

            'email'          => ['required', Rule::unique('users')->ignore($this->donor_id)],

            'phone_number'   => '',

        ]);


        /** seprate the user table data */

        $_user_attribute  =  array(

            'name'           =>  $this->exact_name,

            'exact_id'           =>  $this->donorId,

            'email'          =>  $validatedData['email'],

            'phone_number'   =>  preg_replace('/[()\s-]/', '', $this->phone_number),
        );

        $user = $this->userRepository->whereUpdate(['id' => $this->donor_id], $_user_attribute);

        /** seprate the userprofile table data */

        $nameData = getFirstNameLastName($this->name);

        $_user_profile_attribute  =  array(

            'first_name'          =>   $nameData['first_name'],

            'last_name'           =>    $nameData['last_name'],

            'company'             =>   $this->company_name,

            'gender'              =>   $this->gender,

            'address'             =>   $this->address,

            'city'                =>   $this->city,

            'state'               =>   $this->state,

            'pin_code'            =>   $this->pin_code,

            'project_code'            =>   $this->project_code,

            'country'             =>   $this->countrys,

            'country_code'        =>   $this->country_code,
        );

        $this->userprofileRepository->whereUpdate(['user_id' => $this->donor_id], $_user_profile_attribute);

        $this->toastMessage('Success!', 'Donor Edited Successfully!', '/donor', 'success');
    }

    public function confirmDelete($id)
    {
        $this->donor_id = $id;
    }

    public function delete()
    {
        try {
            $project = $this->projectRepository->wherefirst(['project_donor_id' => $this->donor_id]);

            if ($project) {
                $this->emit('commonModal', 'delete_donor');

                $this->toastMessage('Error!', 'Donor can not be deleted, as it is already allocated on a project.', '', 'error');

                return false;
            }

            $user = $this->userRepository->wherefirst(['id' => $this->donor_id]);

            $this->userprofileRepository->Wheredelete(['user_id' => $this->donor_id]);

            $role = $this->roleRepository->wherefirst(['id' => RoleConstant::DONOR]);

            $user->roles()->detach($role);

            $this->userRepository->Wheredelete(['id' => $this->donor_id]);

            $this->emit('commonModal', 'delete_donor');

            $this->toastMessage('Success!', 'Donor Deleted Successfully!', '/donor', 'success');
        } catch (\Exception $e) {

            $this->emit('swal:alert', [

                'title' => 'Error!',

                'text' => $e->getMessage(),

                'icon' => 'error',

                'status'  => 'error'
            ]);
        }
    }

    /** update donor validation */
    public function updated($name)
    {
        $this->validateOnly($name, [

            'name'           => 'required|max:100',

            'email'          => ['required', Rule::unique('users')->ignore($this->donor_id)],

            'phone_number'   => '',

        ]);
    }


    public function render()
    {
        return view('livewire.donor.edit');
    }

    public function getDonors()
    {
        $this->exactDonors = ExactUsers::all();
    }
}
