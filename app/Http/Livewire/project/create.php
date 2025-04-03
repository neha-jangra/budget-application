<?php

namespace App\Http\Livewire\project;

use Livewire\Component;

use App\Repositories\ProjectRepository;

use App\Repositories\UserRepository;

use App\Repositories\RoleRepository;

use App\Repositories\UserprofileRepository;

use App;

use App\Constants\RoleConstant;

use App\Models\{User, ExactProjects, ProjectYearLinkingWithExact};

use Propaganistas\LaravelPhone\PhoneNumber;

use Illuminate\Support\Str;


class Create extends Component
{
    /** @var  ProjectRepository */
    protected $projectRepository;

    /** @var  UserRepository */
    protected $userRepository;

    /** @var  RoleRepository */
    protected $roleRepository;

    /** @var  UserprofileRepository */
    protected $userProfileRepository;

    public $donors = [], $exactProjects = [], $selected_codes = [], $project_code, $project_name, $project_type, $budget, $project_donor_id, $ecnl_contact, $donor_contact_name, $donor_email, $donor_phone_number, $project_duration_from, $project_duration_to, $current_budget_timeline_from, $current_budget_timeline_to, $date_prepared, $confirm_w_finance, $date_revised, $currency, $loader = true, $message, $user_country, $oldPhoneNumber, $count, $phone_number_country_code, $country, $donor_contract_number, $indirect_rate, $years = [], $exact_project_code, $exact_project_id;

    protected $listeners = ['updateProjectType', 'updateconfirmWfinance', 'updateCurrency', 'updatePhoneNumber', 'updateprojectDonorId', 'updatedProjectDonorId', 'refreshdonor' => 'getDonors', 'updateCountryProject', 'updateCountry', 'generateYears',
        'updateCurrentBudgetTimelineFrom' => 'setCurrentBudgetTimelineFrom',
        'updateCurrentBudgetTimelineTo' => 'setCurrentBudgetTimelineTo', 'updateSelectedCodes', 'updateProjectCode'];

    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {
        $this->projectRepository        = App::make(ProjectRepository::class);

        $this->userRepository           = App::make(UserRepository::class);

        $this->roleRepository           = App::make(RoleRepository::class);

        $this->userProfileRepository    = app(UserprofileRepository::class);
    }

    public function mount()
    {
        session()->forget('selected_codes');
        foreach ($this->years as $year) {
            $this->selected_codes[$year] = session('selected_codes.' . $year, null);  // Default to null if not set
        }
        $this->getDonors();
        $this->getExactProjects();
    }

    public function updateSelectedCodes($year, $value)
    {
        $sessionKey = 'selected_codes.' . $year;
        if (session($sessionKey) !== $value) {
            session([$sessionKey => $value]); 
            $this->selected_codes[$year] = $value; 
        }
        $this->resetErrorBag("selected_codes.$year");
    }

    public function render()
    {
        foreach ($this->years as $year) {
            $this->selected_codes[$year] = session('selected_codes.' . $year, null);  // Default to null if not set
        }
        return view('livewire.project.create');
    }

    protected $rules =
    [
        'project_type' => 'required',
    ];

    public function getDonors()
    {
        $this->donors = User::whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::DONOR);
            }
        )->get();

        $this->loader = false;
    }

    public function validateField($field)
    {
        $this->validateOnly($field, [
            'selected_codes.*' => 'required', // Adjust rules as needed
        ]);
    }

    public function getExactProjects()
    {
        $this->exactProjects = ExactProjects::all();
    }

    public function updateProjectDurationTo($value)
    {
        $this->project_duration_to = $value;
    }

    public function updateProjectDurationfrom($value)
    {
        $this->project_duration_from = $value;
    }

    public function updateProjectType($value)
    {
        $this->project_type = $value;

        $this->resetErrorBag('project_type');
    }

    public function updateprojectDonorId($value)
    {
        $this->project_donor_id = $value;

        $this->resetErrorBag('project_donor_id');
    }

    public function updateconfirmWfinance($value)
    {
        $this->confirm_w_finance = $value;
    }

    public function updateCurrency($value)
    {
       
        $this->currency = $value;

        $this->resetErrorBag('currency');
    }

    public function updatePhoneNumber($value)
    {
        $this->donor_phone_number = $value;
    }

    public function updateOldPhoneNumber()
    {
        $_hasPlusSign = hasPlusSign($this->donor_phone_number);

        if ($_hasPlusSign) {
            $phoneNumber = new PhoneNumber($this->donor_phone_number);

            $this->donor_phone_number    = ltrim($phoneNumber->formatNational(), '0');
        } else {
            $this->donor_phone_number    = ltrim($this->donor_phone_number, '0');
        }
    }
    public function updateProjectCode($value)
    {
        $this->project_code = $value;
        $this->exact_project_id = $value;

        $exactProject = ExactProjects::where('exact_id', $this->project_code)->first();
        if (!$exactProject) return;

        $this->exact_project_code = $exactProject->project_code;
        $this->project_name = $exactProject->description;

        $donor = $exactProject->account ? User::where('exact_id', $exactProject->account)->first() : null;
        if ($donor) {
            $this->fill([
                'project_donor_id'    => $donor->id,
                'donor_email'         => $donor->email,
                'donor_phone_number'  => $donor->phone_number,
                'donor_contact_name'  => $donor->name,
                'user_country'        => $donor->userprofile->country,
            ]);
            // Reset validation errors only if values are set
            collect(['donor_contact_name', 'donor_email', 'donor_phone_number'])
                ->each(fn($field) => $this->$field ? $this->resetErrorBag($field) : null);
        } else {
            $this->reset(['donor_email', 'donor_phone_number', 'donor_contact_name', 'user_country', 'project_donor_id']);
        }
        $this->emit('countrySelected', $this->user_country);
        $this->emit('countrycreatedonornumber', $this->donor_phone_number);
        $this->emit('setProjectDonorId', $this->project_donor_id);

        // Reset validation errors only if values are set
        collect(['project_code', 'project_name'])
        ->each(fn($field) => $this->$field ? $this->resetErrorBag($field) : null);
    }


    public function updateCountryProject($value)
    {
        $this->phone_number_country_code = $value;
    }

    public function updateCountry($value)
    {
        $this->country   = $value;
    }

    public function updatedProjectDonorId($value)
    {
     
        $selectedDonor                = $this->userRepository->wherefirst(['id' => $value]);

        if ($selectedDonor) {
            $this->donor_email        = $selectedDonor->email;

            $this->donor_phone_number = $selectedDonor->phone_number;

            $this->donor_contact_name = $selectedDonor->name;

            $this->user_country       = $selectedDonor->userprofile->country;

            $this->emit('countrySelected', $this->user_country);

            $this->emit('countrycreatedonornumber', $this->donor_phone_number);

            $this->resetErrorBag('donor_contact_name');

            $this->resetErrorBag('donor_email');

            $this->resetErrorBag('donor_phone_number');
        } else {
            $this->donor_email          = '';

            $this->donor_phone_number   = '';

            $this->donor_contact_name   = '';
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {
        $this->project_code = '';

        $this->project_name = '';

        $this->project_type = '';

        $this->budget  = '';

        $this->project_donor_id = '';

        $this->ecnl_contact = '';

        $this->donor_contact_name = '';

        $this->donor_email = '';

        $this->donor_phone_number = '';

        $this->project_duration_from = '';

        $this->project_duration_to = '';

        $this->current_budget_timeline_from = '';

        $this->current_budget_timeline_to = '';

        $this->date_prepared = '';

        $this->confirm_w_finance = '';

        $this->date_revised = '';

        $this->currency     = '';

        $this->donor_contract_number = '';

        $this->indirect_rate = '';
    }

    public function updated($name, $value)
    {
        $this->emit('refreshSelectBox');
        $yearValidationRules = [];
        foreach ($this->years as $year) {
            $yearValidationRules["selected_codes.$year"] = 'required';
        }
        // Merge the dynamic year validation rules with the fixed rules
        $validatedData = $this->validate(array_merge([
            'project_code'          => 'required|unique:projects,exact_id',
            'project_name'          => 'required|max:100',
            'budget'                => 'required',
            'project_donor_id'      => 'required',
            'ecnl_contact'          => 'required|max:100',
            'donor_contact_name'    => 'required|max:100',
            'donor_email'           => 'required|email',
            'project_duration_from' => 'required|date_format:d-m-Y',
            'project_duration_to'   => 'required|date_format:d-m-Y',
            'current_budget_timeline_from' => 'required|date_format:d-m-Y',
            'current_budget_timeline_to'   => 'required|date_format:d-m-Y',
            'donor_phone_number'    => '',
            'indirect_rate'         => '',
        ], $yearValidationRules), [
            'project_code.required'             => 'The project code field is required.',
            'project_code.unique'               => 'This project code is already associated with another project.',
            'project_donor_id.required'         => 'The project donor field is required.',
            'project_duration_to.required'      => 'The project duration field is required.',
            'project_duration_from.required'    => 'The project duration field is required.',
            'project_duration_to.after_or_equal'=> 'The project duration field must be a date after or equal to project duration from.',
            'current_budget_timeline_to.required' => 'The current budget timeline field is required.',
            'current_budget_timeline_from.required' => 'The current budget timeline field is required.',
            'current_budget_timeline_to.after_or_equal' => 'The current budget timeline field must be a date after or equal to current budget timeline from.',
            'ecnl_contact.required'             => 'The ECNL contact field is required.',
            'selected_codes.*.required'         => 'The project code for each year is required.',
        ]);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function store()
    {
        if ($this->project_donor_id === "") {
            $this->project_donor_id = null;
        }
        $this->emit('refreshSelectBox');
        // Dynamically build validation rules for each year
        $yearValidationRules = [];
        foreach ($this->years as $year) {
            $yearValidationRules["selected_codes.$year"] = 'required';
        }
        $validatedData = $this->validate(array_merge([
            'project_code'                => 'required|unique:projects,exact_id',
            'project_name'                => 'required|max:100',
            'budget'                      => 'required',
            'project_donor_id'            => 'required',
            'ecnl_contact'                => 'required|max:100',
            'donor_contact_name'          => 'required|max:100',
            'donor_email'                 => 'required|email',
            'project_duration_from'       => 'required|date_format:d-m-Y',
            'project_duration_to'         => 'required|date_format:d-m-Y',
            'current_budget_timeline_from' => 'required|date_format:d-m-Y',
            'current_budget_timeline_to'  => 'required|date_format:d-m-Y',
            'donor_phone_number'          => '',
        ], $yearValidationRules), [
            'project_code.required'         => 'The project code field is required.',
            'project_code.unique'                    => 'This project code is already associated with another project.',
            'project_donor_id.required'   => 'The project donor field is required.',
            'project_duration_to.required' => 'The project duration field is required.',
            'project_duration_from.required' => 'The project duration field is required.',
            'current_budget_timeline_to.required' => 'The current budget timeline field is required.',
            'current_budget_timeline_from.required' => 'The current budget timeline field is required.',
            'ecnl_contact.required'       => 'The ECNL contact field is required.',
            'selected_codes.*.required'   => 'The project code for each year is required.',
        ]);

   
        $mergedData = array_merge($validatedData, [

            'project_code'                    => $this->exact_project_code,

            'exact_id'                        => $this->exact_project_id,

            'confirm_w_finance'               =>  $this->confirm_w_finance,

            'date_revised'                    => ($this->date_revised) ? date('Y-m-d', strtotime($this->date_revised)) : NULL,

            'project_duration_from'            => date('Y-m-d', strtotime($this->project_duration_from)),

            'project_duration_to'              => date('Y-m-d', strtotime($this->project_duration_to)),

            'current_budget_timeline_to'       => date('Y-m-d', strtotime($this->current_budget_timeline_to)),

            'current_budget_timeline_from'     => date('Y-m-d', strtotime($this->current_budget_timeline_from)),

            'date_prepared'                    => ($this->date_prepared) ? date('Y-m-d', strtotime($this->date_prepared)) : NULL,

            'budget'                           => removeDutchFormat($this->budget),

            'project_type'                     => $this->project_type,

            'donor_phone_number'               => $this->donor_phone_number,

            'currency'                         => $this->currency,

            'donor_contract_number'            => $this->donor_contract_number,

            'indirect_rate'            => $this->indirect_rate ?? 0

        ]);

        $_user_update = array(

            'name'         =>  $this->donor_contact_name,

            'email'        =>  $this->donor_email,

            'phone_number' => $this->donor_phone_number
        );

        $nameData = getFirstNameLastName($this->donor_contact_name);

        $_user_profile_update = array(

            'country'        =>  $this->country,

            'first_name'     =>  isset($nameData['first_name']) ? $nameData['first_name'] : '',

            'last_name'      =>  isset($nameData['last_name'])  ? $nameData['last_name']  : ''
        );

        $project= $this->projectRepository->create($mergedData);
        $this->userRepository->whereUpdate(['id' => $this->project_donor_id], $_user_update);
        $this->userProfileRepository->whereUpdate(['user_id' => $this->project_donor_id], $_user_profile_update);
        foreach ($this->years as $year){
            saveExactLineItemsInBudgetApp($project->id, $year);
        }

        // Store the selected project codes for each year in ProjectYearLinkingWithExact
        foreach ($this->selected_codes as $year => $exactProjectId) {
            $getSubProject = getExactSubProjects($exactProjectId);
            ProjectYearLinkingWithExact::create([
                'project_id'         => $project->id,
                'year'               => $year,
                'exact_project_id'   => $exactProjectId,
                'has_subproject'     => count($getSubProject) > 0,
                'exact_project_code' => ExactProjects::where('exact_id', $exactProjectId)->first()->project_code,
            ]);
        }

        $this->emit('swal:alert', [

            'title'             => 'Success!',

            'text'              => 'Project Created Successfully!',

            'icon'              => 'success',

            'redirectUrl'       => '/project',

            'status'            => 'success'
        ]);
       
    }

    public function setCurrentBudgetTimelineFrom($value)
    {
        $this->current_budget_timeline_from = $value;
        $this->generateYears();
        $this->resetErrorBag('current_budget_timeline_from');
        $this->emit('refreshSelectBox');
    }

    public function setCurrentBudgetTimelineTo($value)
    {
        $this->current_budget_timeline_to = $value;
        $this->generateYears();
        $this->resetErrorBag('current_budget_timeline_to');
        $this->emit('refreshSelectBox');
    }
    
    public function generateYears()
    {
        session()->forget('selected_codes');
        $this->selected_codes = [];
        if ($this->current_budget_timeline_from && $this->current_budget_timeline_to) {
            $fromYear = date('Y', strtotime($this->current_budget_timeline_from));
            $toYear = date('Y', strtotime($this->current_budget_timeline_to));

            if ($fromYear <= $toYear) {
                $this->years = range($fromYear, $toYear);
            } else {
                $this->years = [];
            }
        }
    }
}
