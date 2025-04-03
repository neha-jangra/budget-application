<?php

namespace App\Http\Livewire\IndirectCostsBudget;

use Livewire\Component;
use App\Models\{
    User,
    OtherDirectExpense,
    InDirectExpenseCategories,
    OtherDirectExpensesCalculation,
    OtherDirectExpensesCalculationDraft,
    IndirectExpensesCalculation,
    IndirectExpensesCalculationDraft
};
use App\Constants\RoleConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class IndirectCostsBudget extends Component
{
    protected $listeners = ['updateOdSelectedValue', 'updateIESelectedValue', 'getEmployeeDataForAllTab', 'updateUnits', 'updateCurrentYearExpenses', 'updateOtherIndirectExpenses', 'updateIndirectExpenses', 'getYear', 'getActiveTab'];
    public $selectedValue, $allTabEmployees, $year;
    public $activeTab = 'all';
    public $currentYear;


    public function __construct()
    {
        $this->currentYear = Carbon::now()->year;
    }

    public function render(Request $request)
    {
        $year = $this->currentYear;
        $employees = $this->getEmployees();
        $this->getEmployeeDataForAllTab($year);
        $allOtherDirectExpenses = $this->getAllOtherDirectExpensesForAllTab();

        $otherDirectExpenses = OtherDirectExpense::orderBy('id', 'desc')->with(['calculations' => function ($query) use ($year) {
            $query->where('year', $year);
        }])->where('is_overhead', 1)->get();
        $categories = InDirectExpenseCategories::all();
        return view('livewire.indirect-costs-budget.indirect-costs-budget', ['otherDirectExpenses' => $otherDirectExpenses, 'categories' => $categories, 'activeTab' => $this->activeTab, 'employees' => $employees, 'allOtherDirectExpenses' => $allOtherDirectExpenses]);
    }


    public function getAllOtherDirectExpensesForAllTab()
    {
        $year = $this->currentYear;
        $data = OtherDirectExpense::orderBy('id', 'desc')->with(['calculations' => function ($query) use ($year) {
            $query->where('year', $year);
        }])->where('is_overhead', 1)->get();
        foreach ($data as $key => $value) {
            $value->units = dutchCurrency(calculateSumByOtherDirect('units', $value->id, $year));
            $value->cost_per_unit = dutchCurrency(calculateSumByOtherDirect('cost_per_unit', $value->id, $year));
            $value->total_approved_cost = dutchCurrency(calculateSumByOtherDirect('total_approved_cost', $value->id, $year));
            $value->actual_cost_till_date = dutchCurrency(calculateSumByOtherDirect('actual_cost_till_date', $value->id, $year));
            $value->remaining_cost = dutchCurrency(calculateSumByOtherDirect('remaining_cost', $value->id, $year));
        }
        return $data;
    }

    public function updateOdSelectedValue($type, $value, $expenseId, $categoryId, $rate)
    {
        $value = removeDutchFormat($value ?? 0);
        $updateArray = [
            'indirect_expense_category_id' => $categoryId,
            'other_direct_expense_id' => $expenseId,
            $type => $value ?? 0,
            'cost_per_unit' => $rate,
        ];
        OtherDirectExpensesCalculation::updateOrCreate(
            ['other_direct_expense_id' => $expenseId, 'indirect_expense_category_id' => $categoryId, 'year' => $this->currentYear],
            $updateArray
        );

        $this->emit('hideSaveButton');
        $this->emit('swal:alert:donor', [
            'title' => 'Success!',
            'text' => 'Other Direct Expenses Saved Successfully!',
            'icon' => 'success',
            'status' => 'success'
        ]);
    }

    public function saveOdValue()
    {
        // Get all draft values from OtherDirectExpensesCalculationDraft
        $draftValues = OtherDirectExpensesCalculationDraft::where('year', $this->currentYear)->get();
        // Prepare an associative array with other_direct_expense_id as keys
        $data = $draftValues->keyBy('other_direct_expense_id')->map->getAttributes()->all();
        // Iterate over each element in $data and apply calculatePrice
        foreach ($data as &$element) {
            $element = calculatePrice($element);
        }
        // Update or insert all records in OtherDirectExpensesCalculation using raw SQL
        OtherDirectExpensesCalculation::upsert($data, ['other_direct_expense_id', 'year']);
        $this->emit('hideSaveButton');
        $this->emit('swal:alert:donor', [
            'title'         => 'Success!',
            'text'          => 'Other Direct Expenses Saved Successfully!',
            'icon'          => 'success',
            'status'        => 'success'
        ]);
    }

    public function updateIESelectedValue($type, $value, $employeeId, $categoryId, $rate)
    {
        $value = removeDutchFormat($value ?? 0);
        $updateArray = [
            'indirect_expense_category_id' => $categoryId,
            'employee_id' => $employeeId,
            $type => $value ?? 0,
            'cost_per_unit' => $rate,
        ];
        IndirectExpensesCalculation::updateOrCreate(
            ['employee_id' => $employeeId, 'indirect_expense_category_id' => $categoryId, 'year' => $this->currentYear],
            $updateArray
        );

        $this->emit('hideSaveButton');
        $this->emit('swal:alert:donor', [
            'title' => 'Success!',
            'text' => 'Indirect Expenses Saved Successfully!',
            'icon' => 'success',
            'status' => 'success'
        ]);
    }

    public function saveIEValue($categoryId)
    {
        $draftValues = IndirectExpensesCalculationDraft::where('indirect_expense_category_id', $categoryId)
            ->where('year', $this->currentYear)
            ->get();

        foreach ($draftValues as $draft) {
            $detail = calculatePrice($draft->getAttributes());

            $existingRecord = IndirectExpensesCalculation::where([
                'indirect_expense_category_id' => $draft->indirect_expense_category_id,
                'employee_id' => $draft->employee_id,
                'year' => $draft->year
            ])->first();

            if ($existingRecord && $this->hasChanges($existingRecord, $detail)) {
                $existingRecord->update($detail);
            } elseif (!$existingRecord) {
                IndirectExpensesCalculation::create($detail);
            }
        }

        $this->emit('hideSaveButton');
        $this->emit('swal:alert:donor', [
            'title'         => 'Success!',
            'text'          => 'Indirect Expenses Saved Successfully!',
            'icon'          => 'success',
            'status'        => 'success'
        ]);
    }

    private function hasChanges($existingRecord, $newRecord)
    {
        foreach ($newRecord as $key => $value) {
            if ($existingRecord->$key != $value) {
                return true;
            }
        }
        return false;
    }

    public function getEmployeeDataForAllTab($year)
    {
        $data = $this->getEmployees();
        foreach ($data as $key => $value) {
            $value->units = calculateSumIE('units', $value->id, $year);
            $value->unit_cost = dutchCurrency(calculateAverageDailyRate($value->id, $value->userprofile->rate, $year));
            $value->total_approved_cost = dutchCurrency(calculateSumIE('total_approved_cost', $value->id, $year));
            $value->actual_cost_till_date = dutchCurrency(calculateSumIE('actual_cost_till_date', $value->id, $year));
            $value->remaining_cost = dutchCurrency(calculateSumIE('remaining_cost', $value->id, $year));
        }
        $this->allTabEmployees = $data;
    }

    public function getEmployees()
    {
        $year = $this->currentYear;
        return User::with(['userprofile'])->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::EMPLOYEE);
            }
        )->with(['indirectExpensesCalculations' => function ($query) use ($year) {
            $query->where('year', $year);
        }])->orderBy('id', 'desc')->get();
    }


    public function updateIndirectExpenses($type, $value, $employeeId, $categoryId, $rate)
    {
        $value = removeDutchFormat($value ?? 0);
        $updateArray = [
            'indirect_expense_category_id' => $categoryId,
            'employee_id' => $employeeId,
            $type => $value,
            'cost_per_unit' => $rate,
            'year' => $this->currentYear,
        ];
        $searchCriteria = ['employee_id' => $employeeId, 'indirect_expense_category_id' => $categoryId, 'year' => $this->currentYear];
        $savedData = IndirectExpensesCalculation::updateOrCreate($searchCriteria, $updateArray);
        $detail = calculatePrice($savedData->getAttributes());
        if ($this->hasChanges($savedData, $detail)) {
            $savedData->update($detail);
        }
        $this->emit('hideSaveButton');
        $this->emit('swal:alert:donor', [
            'title' => 'Success!',
            'text' => 'Indirect Expenses Saved Successfully!',
            'icon' => 'success',
            'status' => 'success'
        ]);
    }

    public function updateOtherIndirectExpenses($type, $value, $otherDirectId, $categoryId, $rate)
    {

        $value = removeDutchFormat($value ?? 0);
        $updateArray = [
            'indirect_expense_category_id' => $categoryId,
            'other_direct_expense_id' => $otherDirectId,
            $type => $value,
            'cost_per_unit' => $rate,
            'year' => $this->currentYear,
        ];
        $searchCriteria = ['other_direct_expense_id' => $otherDirectId, 'indirect_expense_category_id' => $categoryId, 'year' => $this->currentYear];
        $savedData = OtherDirectExpensesCalculation::updateOrCreate($searchCriteria, $updateArray);
        $detail = calculatePrice($savedData->getAttributes());
        if ($this->hasChanges($savedData, $detail)) {
            $savedData->update($detail);
        }
        $this->emit('hideSaveButton');
        $this->emit('swal:alert:donor', [
            'title' => 'Success!',
            'text' => 'Other Direct Expenses Saved Successfully!',
            'icon' => 'success',
            'status' => 'success'
        ]);
    }

    public function getYear($year)
    {
        $this->currentYear = $year;
        $this->emit('render');
        $this->emit('yearLoaded');
    }

    public function getActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
}
