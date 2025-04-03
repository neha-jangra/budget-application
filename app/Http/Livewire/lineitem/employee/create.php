<?php

namespace App\Http\Livewire\lineitem\employee;

use Livewire\Component;

use App\Repositories\RoleRepository;

use App\Repositories\UserRepository;

use App\Repositories\PermissionRepository;

use App\Repositories\UserprofileRepository;

use App\Constants\RoleConstant;

use App\Http\Trait\ToastTrait;
use App\Models\ExactEmployee;
use App\Models\ExactUsers;
use App\Models\LineItemDailyRate;

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

    public $position, $first_name, $name, $last_name, $phone_number, $email, $loader = false, $country, $rate, $country_rate, $rate_applicable_from, $country_code, 
    $userExists, $exactEmployees = [], $employee, $exact_name;

    protected $listeners = ['updateCountry', 'updatecountryRate', 'updateEmployee'];

    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {
        $this->roleRepository           = app()->make(RoleRepository::class);

        $this->userRepository           = app()->make(UserRepository::class);

        $this->permissionRepository     = app()->make(PermissionRepository::class);

        $this->userprofileRepository    = app()->make(UserprofileRepository::class);
    }

    public function updateOldPhoneNumber()
    {
        $this->phone_number = removeZeroCountry($this->phone_number);
    }

    public function updatecountryRate($value)
    {
        $this->country_rate   = $value;
    }

    public function updateCountry($value)
    {
        $this->country   = $value;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {

        $this->position              = '';

        $this->name           = '';

        $this->email                = '';

        $this->phone_number         = '';

        $this->rate                 = '';

        $this->country              = '';

        $this->country_rate         = '';
    }

    public function updated($name)
    {
        $this->validateOnly($name, [
            'first_name'            => 'required|max:100',
            'last_name'             => 'required|max:100',
            'email'                 => 'required|email',
            'phone_number'          => '',
            'rate'                  => 'required',
        ]);

        if ($name == 'email') {
            // Check if a user with this email already exists
            $existingUser = $this->userRepository->whereFirst(['email' => $this->email]);
            if ($existingUser) {
                $errorMessage = validateEmail($name, $existingUser);
                if ($errorMessage) {
                    $this->addError('email', $errorMessage);
                    return;
                }
                // If user exists, fill in the details and disable inputs
                $this->first_name = $existingUser->userprofile->first_name;
                $this->last_name = $existingUser->userprofile->last_name;
                $this->userExists = true;
            } else {
                // If user does not exist, enable inputs
                // $this->first_name = '';
                // $this->last_name = '';
                 $this->userExists = false;
            }
        }
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
        $this->rate_applicable_from = $employee->end_rate_date?? date('Y-m-d');
        $this->emit('countryeditSelected', $this->country);
        $this->emit('loadCurrencyFormatter');
        $this->resetErrorBag('employee');
    }

    public function store()
    {
         $validatedData = $this->validate([
            'employee' => 'required',
            'email' => 'nullable|email',
            'rate' => 'required',
            'phone_number' => ''
        ]);

         // Ensure empty email values are converted to null
        if (empty($validatedData['email'])) {
            $validatedData['email'] = null;
        }
        $nameData = getFirstNameLastName($this->exact_name);

        $role = $this->roleRepository->whereFirst(['id' => RoleConstant::EMPLOYEE]);
        $existingUser = $this->userRepository->whereFirst(['email' => $validatedData['email']]);
        if ($existingUser) {
            $errorMessage = validateEmail($validatedData['email'], $existingUser);
            if ($errorMessage) {
                $this->addError('email', $errorMessage);
                return;  // Early return if there's an email error
            }
            $user = $existingUser;
            if ($role && !$user->roles()->where('roles.id', $role->id)->exists()) {
                $user->roles()->attach($role);
            }
            $existingUserProfile = $this->userprofileRepository->whereFirst(['user_id' => $user->id]);
            $profileData = [
                'position' => $this->position,
                'first_name' => $nameData['first_name'],
                'last_name' => $nameData['last_name'],
                'rate' => removeDutchFormat($validatedData['rate']),
                'country' => $this->country,
                'country_rate' => $this->country_rate,
            ];
            if ($existingUserProfile) {
                $existingUserProfile->update($profileData);
            } else {
                $profileData['user_id'] = $user->id;
                $this->userprofileRepository->create($profileData);
            }
        } else {
            $userAttributes = [
                'exact_id' => $this->employee,
                'name' => $this->exact_name,
                'email' => $validatedData['email'],
                'phone_number' => preg_replace('/[()\s-]/', '', $this->phone_number),
            ];
            $user = $this->userRepository->create($userAttributes);

            if ($role) {
                $user->roles()->attach($role);
            }

            $profileData = [
                'position' => $this->position,
                'first_name' => $nameData['first_name'],
                'last_name' => $nameData['last_name'],
                'rate' => removeDutchFormat($validatedData['rate']),
                'user_id' => $user->id,
                'country' => $this->country,
                'country_rate' => $this->country_rate,
            ];
            $this->userprofileRepository->create($profileData);

            $permission = $this->permissionRepository->whereFirst(['id' => RoleConstant::EMPLOYEE]);
            if ($permission && !$role->permissions()->where('id', $permission->id)->exists()) {
                $role->permissions()->attach($permission);
            }
        }
        $currency = $this->country_rate ?? 'eur';
        $applicableDate = $this->rate_applicable_from ?? date('Y-m-d');
        $rate = removeDutchFormat($validatedData['rate']);

        if (!LineItemDailyRate::where([
            'user_id' => $user->id,
            'currency' => $currency,
            'rate_applicable_from' => now()->parse($applicableDate)->format('Y-m-d'),
            'rate' => $rate,
        ])->exists()) {
            LineItemDailyRate::create([
                'user_id' => $user->id,
                'currency' => $currency,
                'rate' => $rate,
                'rate_applicable_from' => $applicableDate,
            ]);
        }

        $this->toastMessage('Success!', 'Employee Created Successfully!', '/line-item?active_tab=tab3', 'success');
    }

    public function render()
    {
        $this->getEmployees();
        return view('livewire.lineitem.employee.create');
    }

    public function getEmployees()
    {
        $this->exactEmployees = ExactEmployee::all();
    }
}
