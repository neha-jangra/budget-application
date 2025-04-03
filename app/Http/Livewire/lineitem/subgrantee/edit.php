<?php

namespace App\Http\Livewire\lineitem\subgrantee;

use Livewire\Component;

use Illuminate\Support\Facades\Route;

use Illuminate\Validation\Rule;

use App\Repositories\UserRepository;

use App\Repositories\UserprofileRepository;

use App\Repositories\RoleRepository;

use App\Repositories\SubProjectDataRepository;

use App\Models\User;

use App\Constants\RoleConstant;

use App\Http\Trait\ToastTrait;

class Edit extends Component
{
    use ToastTrait;

    /** @var  UserRepository */
    protected $userRepository;

    /** @var  UserprofileRepository */
    protected $userprofileRepository;

    /** @var  RoleRepository */
    protected $roleRepository;

    /** @var  SubProjectDataRepository */
    protected $subProjectDataRepository;

    public $company, $first_name, $last_name, $phone_number, $email, $loader = false, $country, $rate, $userdetail, $user_id, $user_country, $lineitem_id, $country_rate, $rate_applicable_from, $userDailyRate, $userExists;

    protected $listeners = ['updateCountry', 'updatedProjectDonorId', 'updatecountryRate'];

    public function hydrate()
    {
        $this->setRepository();
    }

    public function mount()
    {
        $this->user_id                    = Route::current()->parameter('subgrantee');

        $this->userdetail                 = User::where(['id' => $this->user_id])->first();

        $this->userDailyRate              = $this->userdetail->userDailyRates()->orderBy('rate_applicable_from', 'desc')->first();

        $this->first_name                 = isset($this->userdetail->userprofile->first_name) ? $this->userdetail->userprofile->first_name  : '';

        $this->last_name                  = isset($this->userdetail->userprofile->last_name)  ? $this->userdetail->userprofile->last_name   : '';

        $this->email                      = isset($this->userdetail->email)                   ? $this->userdetail->email                    : '';

        $this->phone_number               = isset($this->userdetail->phone_number)            ? $this->userdetail->phone_number             : '';

        $this->company                    = isset($this->userdetail->userprofile->company)    ? $this->userdetail->userprofile->company     : '';

        $this->rate                       = isset($this->userdetail->userprofile->rate)       ? netherlandformatCurrency($this->userdetail->userprofile->rate)        : '';

        $this->country_rate               = isset($this->userdetail->userprofile->country_rate) ? $this->userdetail->userprofile->country_rate : '';

        $this->rate_applicable_from       = (isset($this->userDailyRate->rate_applicable_from)) ? date('d-m-Y', strtotime($this->userDailyRate->rate_applicable_from)) : NULL;
    }

    public function setRepository()
    {

        $this->userRepository            = app()->make(UserRepository::class);

        $this->userprofileRepository     = app()->make(UserprofileRepository::class);

        $this->roleRepository            = app()->make(RoleRepository::class);

        $this->subProjectDataRepository  = app()->make(SubProjectDataRepository::class);
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

    public function updatedProjectDonorId($value)
    {
        $selectedDonor                = $this->userRepository->wherefirst(['id' => $value]);

        if ($selectedDonor) {

            $this->user_country       = isset($selectedDonor->userprofile->country) ? $selectedDonor->userprofile->country : '';

            $this->emit('countryeditSelected', $this->user_country);
        }
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
            'rate'                  => '',
        ]);

        if ($name == 'email' && $this->email) {
            if ($this->userdetail && $this->userdetail->email == $this->email) {
                // Skip validation if the email is unchanged
                return;
            }
            $existingUser = $this->userRepository->whereFirst(['email' => $this->email]);
            $errorMessage = validateEmail($this->email, $existingUser);
            if ($errorMessage) {
                $this->addError('email', $errorMessage);
                return;
            }
            if ($existingUser) {
                $this->first_name = $existingUser->userprofile->first_name;
                $this->last_name = $existingUser->userprofile->last_name;
                $this->userExists = true;
            } else {
                $this->userExists = false;
            }
        }
    }

    public function edit()
    {
        $validatedData = $this->validate([

            'first_name'            => 'required|max:100',

            'last_name'             => 'required|max:100',

            'email'                 => 'nullable|email',

            'rate'                  => '',
        ]);
        $user = $this->userRepository->find($this->user_id);

        $role = $this->roleRepository->whereFirst(['id' => RoleConstant::SUBGRANTEE]);
        if ($user->email !== $validatedData['email']) {
            $existingUser = $this->userRepository->whereFirst(['email' => $validatedData['email']]);
            $errorMessage = validateEmail($validatedData['email'], $existingUser);
            if ($errorMessage) {
                $this->addError('email', $errorMessage);
                return;  // Early return if there's an email error
            } else {
               if($existingUser){
                    updateLineItem($role, $existingUser, $user);
                    $this->user_id = $existingUser->id;
                } 
            }
        }

        // Ensure empty email values are converted to null
        if (empty($validatedData['email'])) {
            $validatedData['email'] = null;
        }

        /** seprate the user table data */

        $_user_attribute  =  array(

            'name'           =>  $validatedData['first_name'] . ' ' . $validatedData['last_name'],

            'email'          =>  $validatedData['email'],

            'phone_number'   =>  preg_replace('/[()\s-]/', '', $this->phone_number),
        );

        $user = $this->userRepository->whereUpdate(['id' => $this->user_id], $_user_attribute);

       


        /** seprate the userprofile table data */

        $_user_profile_attribute  =  array(

            'company'             =>  $this->company,

            'first_name'          =>  $validatedData['first_name'],

            'last_name'           =>  $validatedData['last_name'],

            'rate'                =>  isset($validatedData['rate']) ? removeDutchFormat($validatedData['rate']) : 1,

            'country'             =>  $this->country,

            'country_rate'        =>  $this->country_rate

        );

        $this->userprofileRepository->whereUpdate(['user_id' => $this->user_id], $_user_profile_attribute);
        $this->toastMessage('Success!', 'Sub-grantee updated Successfully!', '/line-item?active_tab=tab2', 'success');
    }

    public function confirmDelete($id)
    {
        $this->lineitem_id = $id;
    }

    public function delete()
    {
        try {
            $subProjectdata = $this->subProjectDataRepository->wherefirst(['employee_id' => $this->user_id]);
            if ($subProjectdata) {
                $this->emit('commonModal', 'delete_lineItem_sub_grantee');
                $this->toastMessage('Error!', 'Sub-grantee can not be deleted, as it is already allocated on a project.', '', 'error');
                return false;
            }
            $user = $this->userRepository->wherefirst(['id' => $this->user_id]);
            $role = $this->roleRepository->wherefirst(['id' => RoleConstant::SUBGRANTEE]);
            $user->roles()->detach($role);
            if (count($user->roles()->get()) == 0) {
                $this->userprofileRepository->Wheredelete(['user_id' => $this->user_id]);
                $this->userRepository->Wheredelete(['id' => $this->user_id]);
            }
            $this->emit('commonModal', 'delete_lineItem_sub_grantee');

            $this->toastMessage('Success!', 'Sub-grantee Deleted Successfully!', '/line-item?active_tab=tab2', 'success');
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
        return view('livewire.lineitem.subgrantee.edit');
    }
}
