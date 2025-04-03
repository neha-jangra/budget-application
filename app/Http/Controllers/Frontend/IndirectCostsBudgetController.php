<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Livewire\Livewire;
use App\Models\{User, OtherDirectExpense, SubProjectData, Project};
use App\Constants\RoleConstant;

class IndirectCostsBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('indirect-costs-budget.index')->with('livewire', Livewire::mount('indirect-costs-budget.indirect-costs-budget'));
    }


    public function allTabData(Request $request)
    {
        $year = $request->year ?? date('');
        $allTabEmployees = $this->getEmployeeDataForAllTab($year);
        $allOtherDirectExpenses = $this->getAllOtherDirectExpensesForAllTab($year);
        return view('components.indirect-other-cost.all-tab', ['allTabEmployees' => $allTabEmployees, 'allOtherDirectExpenses' => $allOtherDirectExpenses, 'currentYear' => $year]);
    }

    public function getEmployeeDataForAllTab($year)
    {
        $data = $this->getEmployees();
        foreach ($data as $key => $value) {
            $value->units                 = calculateSumIE('units', $value->id, $year);
            $value->unit_cost             = dutchCurrency(calculateAverageDailyRate($value->id, $value->userprofile->rate, $year));
            $value->total_approved_cost   = dutchCurrency(calculateSumIE('total_approved_cost', $value->id, $year));
            $value->actual_cost_till_date = dutchCurrency(calculateSumIE('actual_cost_till_date', $value->id, $year));
            $value->remaining_cost        = dutchCurrency(calculateSumIE('remaining_cost', $value->id, $year));
        }
        return $data;
    }

    public function getAllOtherDirectExpensesForAllTab($year)
    {
        $data = OtherDirectExpense::orderBy('id', 'desc')->with(['calculations' => function ($query) use ($year) {
            $query->where('year', $year);
        }])->where('is_overhead', 1)->get();
        foreach ($data as $key => $value) {
            $value->units                 = dutchCurrency(calculateSumByOtherDirect('units', $value->id, $year));
            $value->cost_per_unit         = dutchCurrency(calculateSumByOtherDirect('cost_per_unit', $value->id, $year));
            $value->total_approved_cost   = dutchCurrency(calculateSumByOtherDirect('total_approved_cost', $value->id, $year));
            $value->actual_cost_till_date = dutchCurrency(calculateSumByOtherDirect('actual_cost_till_date', $value->id, $year));
            $value->remaining_cost        = dutchCurrency(calculateSumByOtherDirect('remaining_cost', $value->id, $year));
        }
        return $data;
    }

    public function getEmployees()
    {
        return User::with(['userprofile', 'indirectExpensesCalculations'])->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::EMPLOYEE);
            }
        )->orderBy('id', 'desc')->get();
    }

    public function getOtherDirectExpenses(Request $request)
    {
        $data = $request->all();
        if (!empty($data['sub_project_id'])) {
            $selectedOtherExpenses = SubProjectData::where(['project_id' => $data['project_id'], 'sub_project_id' => $data['sub_project_id'], 'year' => $data['year'], 'project_hierarchy_id' => 6])->pluck('employee_id')->toArray();
        } else {
            $selectedOtherExpenses = SubProjectData::where(['project_id' => $data['project_id'], 'year' => $data['year'], 'project_hierarchy_id' => 6])->pluck('employee_id')->toArray();
        }
        $result =
            OtherDirectExpense::whereNotIn('id', $selectedOtherExpenses)
            ->where('is_project', 1)
            ->orderBy('name')
            ->get();
        $project  = Project::select('currency')->where(['id' => $data['project_id']])->first();
        return ['user' => $result, 'project' => $project];
    }
}
