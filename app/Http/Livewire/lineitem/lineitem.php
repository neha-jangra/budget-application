<?php

namespace App\Http\Livewire\lineitem;

use Livewire\Component;

use Livewire\WithPagination;

use App\Models\User;

use App\Models\OtherDirectExpense;

use App\Repositories\UserRepository;

use App\Repositories\UserprofileRepository;

use App\Repositories\RoleRepository;

use App\Repositories\SubProjectDataRepository;

use App\Constants\RoleConstant;

use App\Http\Trait\ToastTrait;

use Illuminate\Support\Collection;

use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Http\Request;

class lineitem extends Component
{
    use WithPagination, ToastTrait;

    /** @var  UserRepository */
    protected $userRepository;

    /** @var  UserprofileRepository */
    protected $userprofileRepository;

    /** @var  RoleRepository */
    protected $roleRepository;

    /** @var  SubProjectDataRepository */
    protected $subProjectDataRepository;

    public $activeTab = 'tab1', $isLoading = true, $recordsPerPage = 50, $search_by_name, $type_name, $tabData, $lineitem_id, $type_id, $tabData_id, $user_id;

    protected $consultants = [], $subgrantees = [], $employee = [], $paginate;

    public $name = '';
    public $is_overhead = false;
    public $is_project = false;


    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {
        $this->userRepository            = app()->make(UserRepository::class);

        $this->userprofileRepository     = app()->make(UserprofileRepository::class);

        $this->roleRepository            = app()->make(RoleRepository::class);

        $this->subProjectDataRepository  = app()->make(SubProjectDataRepository::class);
    }

    public function mount(Request $request)
    {
        $activeTabFromQuery = $request->query('active_tab');
        if ($activeTabFromQuery && in_array($activeTabFromQuery, ['tab1', 'tab2', 'tab3', 'tab4'])) {
            $this->activeTab = $activeTabFromQuery;
        }
        $this->fetchTabData('tab1');
    }

    public function getlineitem()
    {
        usleep(50000);

        $this->isLoading = false;
    }

    public function updatedRecordsPerPage()
    {
        $this->resetPage();
    }

    public function clearInput()
    {
        $this->search_by_name = '';
    }

    public function switchTab($tab)
    {
        $this->resetPage();

        $this->isLoading = true;

        $this->fetchTabData($tab);

        $this->activeTab = $tab;

        $this->isLoading = false;

        $this->search_by_name = '';
    }

    public function updatedsearchByName()
    {
        $this->fetchTabData($this->activeTab);
    }

    public function clearModal()
    {
        $this->name = '';
        $this->is_overhead = false;
        $this->is_project = false;
        $this->resetValidation();
    }

    private function fetchTabData($tab)
    {
        $this->setRepository();

        if ($tab === 'tab1') {
            $this->consultants = $this->userRepository->with(['userprofile'])->whereHas(
                'roles',
                function ($roles) {
                    $roles->where('role_id', '=', RoleConstant::CONSULTANT);
                }
            )->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search_by_name . '%');
            })->orderBy('id', 'desc')->paginate($this->recordsPerPage);
        } elseif ($tab === 'tab2') {
            $this->subgrantees = $this->userRepository->with(['userprofile'])->whereHas(
                'roles',
                function ($roles) {
                    $roles->where('role_id', '=', RoleConstant::SUBGRANTEE);
                }
            )->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search_by_name . '%');
            })->orderBy('id', 'desc')->paginate($this->recordsPerPage);
        } elseif ($tab === 'tab3') {
            $this->employee = $this->userRepository->with(['userprofile'])->whereHas(
                'roles',
                function ($roles) {
                    $roles->where('role_id', '=', RoleConstant::EMPLOYEE);
                }
            )->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search_by_name . '%');
            })->orderBy('id', 'desc')->paginate($this->recordsPerPage);
        }
    }

    public function confirmDelete($id, $type_id)
    {
        $this->lineitem_id = $id;
        $this->type_id = $type_id;
    }

    public function delete()
    {
        if ($this->type_id == 3) {
            $this->type_name   = 'Employee';

            $this->tabData     = 'tab3';

            $this->tabData_id  = 'delete_lineItem_employee';
        } else if ($this->type_id == 4) {
            $this->type_name = 'Sub-grantee';

            $this->tabData = 'tab2';

            $this->tabData_id  = 'delete_lineItem_sub_grantee';
        } else if ($this->type_id == 5) {
            $this->type_name = 'Consultant';

            $this->tabData = 'tab1';

            $this->tabData_id  = 'delete_lineItem_consultant';
        }

        try {

            $subProjectdata = $this->subProjectDataRepository->wherefirst(['employee_id' => $this->lineitem_id]);

            if ($subProjectdata) {

                $this->emit('commonModal', $this->tabData_id);

                $this->toastMessage('Error!', $this->type_name . ' can not be deleted, as it is already allocated on a project.', '', 'error');

                return false;
            }

            $user = $this->userRepository->wherefirst(['id' => $this->lineitem_id]);
            $role = $this->roleRepository->wherefirst(['id' => $this->type_id]);

            $user->roles()->detach($role);
            if (count($user->roles()->get()) == 0) {
                $this->userprofileRepository->Wheredelete(['user_id' => $this->lineitem_id]);
                $this->userRepository->Wheredelete(['id' => $this->lineitem_id]);
            }

            $this->emit('commonModal', $this->tabData_id);

            $this->toastMessage('Success!', $this->type_name . ' Deleted Successfully!', '', 'success');

            $this->activeTab = $this->tabData;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            $this->toastMessage('Error!', 'We can not remove the ' . $this->type_name . ' as they are essential to the project.', '', 'error');
        }
    }

    public function deleteOtherDirectExpense()
    {
        try {
            OtherDirectExpense::where('id', $this->lineitem_id)->delete();
            $this->emit('commonModal', 'delete_other_direct_expenses');
            $this->toastMessage('Success!', 'Other Direct Expense Deleted Successfully!', '', 'success');
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
        $this->consultants = $this->userRepository->with(['userprofile'])->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::CONSULTANT);
            }
        )->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search_by_name . '%');
        })->orderBy('id', 'desc')->paginate($this->recordsPerPage);

        $consulatntDataExists = $this->userRepository->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::CONSULTANT);
            }
        )->count() > 0;

        $this->subgrantees = $this->userRepository->with(['userprofile'])->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::SUBGRANTEE);
            }
        )->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search_by_name . '%');
        })->orderBy('id', 'desc')->paginate($this->recordsPerPage);

        $subgranteeDataExists = $this->userRepository->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::SUBGRANTEE);
            }
        )->count() > 0;

        $this->employee = $this->userRepository->with(['userprofile'])->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::EMPLOYEE);
            }
        )->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search_by_name . '%');
        })->orderBy('id', 'desc')->paginate($this->recordsPerPage);

        $employeeDataExists = $this->userRepository->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::EMPLOYEE);
            }
        )->count() > 0;

        // Other direct expenses
        $otherDirectExpenses =  OtherDirectExpense::where('name', 'like', '%' . $this->search_by_name . '%')->orderBy('id', 'DESC')->paginate($this->recordsPerPage);

        $otherDirectExpensesExists = $otherDirectExpenses->count() > 0;

        return view('livewire.lineitem.lineitem', ['consultants' => $this->consultants, 'paginate' => $this->paginate, 'subgrantees' => $this->subgrantees, 'employees' => $this->employee, 'consulatntDataExists' => $consulatntDataExists, 'subgranteeDataExists' => $subgranteeDataExists, 'employeeDataExists' => $employeeDataExists, 'otherDirectExpensesExists' => $otherDirectExpensesExists, 'otherDirectExpenses' => $otherDirectExpenses]);
    }


    public function createOtherDirectExpense()
    {
        $validatedData = $this->validate([
            'name' => 'unique:other_direct_expenses|required|max:100',
            'is_overhead' => 'boolean',
            'is_project' => 'boolean'
        ]);
        $OtherDirectExpense = OtherDirectExpense::create($validatedData);
        $this->render();
        $this->emit('close-modal-other-direct-expense');
        $this->toastMessage('Success!', 'Other Direct Expense Created Successfully!', '', 'success');
    }

    public function confirmDeleteOtherDirect($id)
    {
        $this->lineitem_id = $id;
    }

    public function editOtherDirect($id)
    {
        $this->resetValidation();
        $detail = OtherDirectExpense::where('id', $id)->first();
        $this->lineitem_id = $id;
        $this->name = $detail->name;
        $this->is_overhead = $detail->is_overhead;
        $this->is_project = $detail->is_project;
    }

    public function updateOtherDirectExpense()
    {
        $validatedData = $this->validate([
            'name' => 'required|max:100|unique:other_direct_expenses,name,' . $this->lineitem_id,
            'is_overhead' => 'boolean',
            'is_project' => 'boolean'
        ]);
        $OtherDirectExpense = OtherDirectExpense::find($this->lineitem_id);
        $OtherDirectExpense->update($validatedData);
        $this->emit('close-modal-other-direct-expense');
        $this->toastMessage('Success!', 'Other Direct Expense Updated Successfully!', '', 'success');
    }

    /** update donor validation */
    public function updated($name)
    {
        if ($this->lineitem_id == '') {
            $this->validateOnly($name, [
                'name'           => 'unique:other_direct_expenses|required|max:100',
                'is_overhead' => 'boolean',
                'is_project' => 'boolean',
            ]);
        } else {
            $this->validateOnly($name, [
                'name'           => 'required|max:100|unique:other_direct_expenses,name,' . $this->lineitem_id,
                'is_overhead' => 'boolean',
                'is_project' => 'boolean'
            ]);
        }
    }
}
