<?php

namespace App\Http\Livewire\lineitem\consultant;

use Livewire\Component;

use App\Repositories\RoleRepository;

use App\Repositories\UserRepository;

use App\Repositories\PermissionRepository;

use App\Repositories\UserprofileRepository;

use App\Constants\RoleConstant;

use App\Http\Trait\ToastTrait;
use App\Models\{LineItemDailyRate};

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

    public $company, $first_name, $last_name, $phone_number, $email, $loader = false, $country, $rate, $country_rate, $rate_applicable_from, $userExists;

    protected $listeners = ['updateCountry', 'updatecountryRate'];

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

    public function updateCountry($value)
    {
        $this->country   = $value;
    }

    public function updatecountryRate($value)
    {
        $this->country_rate   = $value;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {

        $this->company              = '';

        $this->first_name           = '';

        $this->first_name           = '';

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
            'email'                 => 'nullable|email',
            'rate'                  => 'required',
        ]);

        if ($name == 'email') {
            $existingUser = $this->userRepository->whereFirst(['email' => $this->email]);
            if ($existingUser) {
                $errorMessage = validateEmail($name, $existingUser);
                if ($errorMessage) {
                    $this->addError('email', $errorMessage);
                    return;  // Early return if there's an email error
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

    public function store()
    {
        $validatedData = $this->validate([
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'nullable|email',
            'rate' => 'required',
        ]);
        $existingUser = $this->userRepository->whereFirst(['email' => $validatedData['email']]);
        // Ensure empty email values are converted to null
        if (empty($validatedData['email'])) {
            $validatedData['email'] = null;
        }
        $role = $this->roleRepository->whereFirst(['id' => RoleConstant::CONSULTANT]);
        
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
                'company' => $this->company,
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
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
                'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone_number' => preg_replace('/[()\s-]/', '', $this->phone_number),
            ];
            $user = $this->userRepository->create($userAttributes);

            if ($role) {
                $user->roles()->attach($role);
            }

            $profileData = [
                'company' => $this->company,
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'rate' => removeDutchFormat($validatedData['rate']),
                'user_id' => $user->id,
                'country' => $this->country,
                'country_rate' => $this->country_rate,
            ];
            $this->userprofileRepository->create($profileData);

            $permission = $this->permissionRepository->whereFirst(['id' => RoleConstant::CONSULTANT]);
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

        $this->toastMessage('Success!', 'Consultant Created Successfully!', '/line-item?active_tab=tab1', 'success');
    }

    public function render()
    {
        return view('livewire.lineitem.consultant.create');
    }
}
