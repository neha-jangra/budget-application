<?php

namespace App\Http\Livewire\lineitem\employee;

use Livewire\Component;

use Illuminate\Support\Facades\Route;

use Illuminate\Validation\Rule;

use App\Repositories\UserRepository;

use App\Repositories\UserprofileRepository;

use App\Repositories\RoleRepository;

use App\Repositories\SubProjectDataRepository;

use App\Models\{User, LineItemDailyRate, IndirectExpensesCalculation, ExactEmployee};

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

    public $position, $name, $first_name, $last_name, $phone_number, $email, $loader = false, $country, $rate, $userdetail, $user_id, $user_country, $type_name, $lineitem_id, $country_rate, $rate_applicable_from, $userDailyRate, $userExists, $checkEmail=true, $country_code,
        $exactEmployees = [], $employee, $exact_name;

    protected $listeners = ['updateCountry', 'updatedProjectDonorId', 'updatecountryRate', 'updateEmployee'];

    public function hydrate()
    {
        $this->setRepository();
    }

    public function mount()
    {
        $this->user_id                    = Route::current()->parameter('employee');

        $this->userdetail                 = User::where(['id' => $this->user_id])->first();

        $this->employee                   = $this->userdetail->exact_id;

        $this->exact_name                   = $this->userdetail->name;

        $this->userDailyRate              = $this->userdetail->userDailyRates()->orderBy('rate_applicable_from', 'desc')->first();

        $this->first_name                 = isset($this->userdetail->userprofile->first_name) ? $this->userdetail->userprofile->first_name  : '';

        $this->last_name                  = isset($this->userdetail->userprofile->last_name)  ? $this->userdetail->userprofile->last_name   : '';

        $this->email                      = isset($this->userdetail->email)                   ? $this->userdetail->email                    : '';

        $this->phone_number               = isset($this->userdetail->phone_number)            ? $this->userdetail->phone_number             : '';

        $this->position                    = isset($this->userdetail->userprofile->position)    ? $this->userdetail->userprofile->position     : '';

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

    public function updateEmployee($value)
    {
        $this->employee = $value;
        $this->name   = $value;
        $employee = ExactEmployee::where('exact_id', $value)->first();
        $this->exact_name =  $employee->full_name;
        $this->email =   $employee->email;
        $phone = preg_replace('/[^\d\+]/', '', $this->phone_number);
        $countryCode = substr($phone, 0, strpos($phone, '0') === 0 ? 3 : 2);
        $phoneNumber = substr($phone, strlen($countryCode));
        $this->phone_number = $phoneNumber;
        $this->country = $employee->country;
        $this->country_code = $countryCode;
        $this->rate = dutchCurrency($employee->rate);
        $this->rate_applicable_from = $employee->end_rate_date ?? date('Y-m-d');
        $this->emit('countryeditSelected', $this->country);
        $this->emit('loadCurrencyFormatter');
        $this->resetErrorBag('employee');
    }


    public function updateCountry($value)
    {
        $this->country   = $value;
    }

    public function updatecountryRate($value)
    {
        $this->country_rate   = $value;
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

        $this->position              = '';

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
            'name'    => 'required',
            'email'         => 'nullable|email',
            'phone_number'  => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'rate'          => 'required',
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
            'employee'    => 'required',
            'email'         => 'nullable|email',
            'rate'          => 'required',
        ]);
        $nameData = getFirstNameLastName($this->exact_name);
        $user = $this->userRepository->find($this->user_id);

        $role = $this->roleRepository->whereFirst(['id' => RoleConstant::EMPLOYEE]);
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
        $_user_attribute = [
            'exact_id'      => $this->employee,
            'name' => $this->exact_name,
            'email'         => $validatedData['email'],
            'phone_number'  => preg_replace('/[()\s-]/', '', $this->phone_number),
        ];

        $this->userRepository->whereUpdate(['id' => $this->user_id], $_user_attribute);

        $_user_profile_attribute = [
            'position'          => $this->position,
            'first_name'        => $nameData['first_name'],
            'last_name'         => $nameData['last_name'],
            'rate'              => isset($validatedData['rate']) ? removeDutchFormat($validatedData['rate']) : 1,
            'country'           => $this->country,
            'country_rate'      => $this->country_rate,
        ];

        $currency = !empty($this->country_rate) ? $this->country_rate : 'eur';
        $applicableDate = $this->rate_applicable_from ?? date('Y-m-d');
        $rate = isset($validatedData['rate']) ? removeDutchFormat($validatedData['rate']) : 1;

        if (!LineItemDailyRate::where([
            'user_id'              => $this->user_id,
            'currency'             => $currency,
            'rate_applicable_from' => now()->parse($applicableDate)->format('Y-m-d'),
            'rate'                 => $rate,
        ])->first()) {
            LineItemDailyRate::create([
                'user_id'              => $this->user_id,
                'currency'             => $currency,
                'rate'                 => $rate,
                'rate_applicable_from' => $applicableDate,
            ]);
            $this->updateIndirectCost($rate);
        }

        $this->userprofileRepository->whereUpdate(['user_id' => $this->user_id], $_user_profile_attribute);
        $this->toastMessage('Success!', 'Employee Updated Successfully!', '/line-item?active_tab=tab3', 'success');
    }


    public function updateIndirectCost($rate)
    {
        $costs = IndirectExpensesCalculation::where('employee_id', $this->user_id)->where('year', date('Y'))->get();
        foreach ($costs as $key => $value) {
            $value->cost_per_unit = calculateAverageDailyRate($this->user_id, $rate);
            $value->total_approved_cost = calculateAverageDailyRate($this->user_id, $rate) * $value->units;
            $value->remaining_cost = $value->total_approved_cost -  $value->actual_cost_till_date;
            $value->save();
        }
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
                $this->emit('commonModal', 'delete_lineItem_employee');

                $this->toastMessage('Error!', 'Employee can not be deleted, as it is already allocated on a project.', '', 'error');

                return false;
            }

            $user = $this->userRepository->wherefirst(['id' => $this->user_id]);
            $role = $this->roleRepository->wherefirst(['id' => RoleConstant::EMPLOYEE]);
            $user->roles()->detach($role);
            if (count($user->roles()->get()) == 0) {
                $this->userprofileRepository->Wheredelete(['user_id' => $this->user_id]);
                $this->userRepository->Wheredelete(['id' => $this->user_id]);
            }

            $this->emit('commonModal', 'delete_lineItem_employee');

            $this->toastMessage('Success!', 'Employee Deleted Successfully!', '/line-item?active_tab=tab3', 'success');
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
        $this->getEmployees();
        return view('livewire.lineitem.employee.edit');
    }
    public function getEmployees()
    {
        $this->exactEmployees = ExactEmployee::all();
    }
}
