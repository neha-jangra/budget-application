<?php

namespace App\Http\Livewire\Reports;

use App\Models\{IndirectExpensesCalculation, Project, SubProjectData, User, AnnualReport, InDirectExpenseCategories, OtherDirectExpense};
use Livewire\Component;
use App\Repositories\{SubProjectDataRepository, ProjectDetailRepository};
use App\Constants\RoleConstant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


class Reports extends Component
{
    public $indirectCostData, $year;
    protected $subProjectDataRepository, $projectDetailRepository;

    protected $listeners = ['budgetOverview', 'saveReportData', 'updateProjectUnits', 'render', 'getYear', 'rowToggled'];

    public function __construct()
    {
        $projectYears = getYearList();
        if (count($projectYears) == 1) {
            $this->year = $projectYears[0];
        } else {
            $this->year = date('Y');
        }
    }

    public function setRepository()
    {
        $this->subProjectDataRepository  = app(SubProjectDataRepository::class);
        $this->projectDetailRepository  = app(ProjectDetailRepository::class);
    }

    public function render(Request $request)
    {
        $year = $this->year;
        $totalBudgets = $this->getTotalBudgets($year);
        $categories = InDirectExpenseCategories::all();
        $employees = $this->getEmployeeData($year);
        $otherDirectExpenses = $this->getOtherDirectExpenses($year);
        $donors = $this->getDonorData($year);
        $lineItemsOtherDirectExpenses = $this->getLineItemOtherDirectExpenses($year);
        $projects = $this->getProject($year);
        $lastUpdate = AnnualReport::where('year', $this->year)->orderBy('updated_at')->first();
        return view('livewire.reports.reports', ['totalBudgets' => $totalBudgets, 'employees' => $employees, 'donors' => $donors, 'otherDirectExpenses' => $otherDirectExpenses, 'categories' => $categories, 'projects' => $projects, 'lastUpdate' => $lastUpdate, 'lineItemsODE' => $lineItemsOtherDirectExpenses]);
    }

    public function export()
    {
        $year = $this->year;
        $totalBudgets = $this->getTotalBudgets($year);
        $categories = InDirectExpenseCategories::all();
        $employees = $this->getEmployeeData($year);
        $otherDirectExpenses = $this->getOtherDirectExpenses($year);
        $donors = $this->getDonorData($year);
        $lineItemsOtherDirectExpenses = $this->getLineItemOtherDirectExpenses($year);
        $projects = $this->getProject($year);
        $lastUpdate = AnnualReport::where('year', $this->year)->orderBy('updated_at')->first();
        return view('livewire.reports.export', ['totalBudgets' => $totalBudgets, 'employees' => $employees, 'donors' => $donors, 'otherDirectExpenses' => $otherDirectExpenses, 'categories' => $categories, 'projects' => $projects, 'lastUpdate' => $lastUpdate, 'lineItemsODE' => $lineItemsOtherDirectExpenses]);
    }

    public function budgetOverview()
    {
        $year = $this->year;
        $directCostData = $this->getDirectCost($year);
        $indirectCostData = $this->getIndirectCost($year);
        $donors = $this->getDonorData($year);
        $incomeChartData = $this->prepareIncomeChartData($donors);

        // Prepare an array to hold data for all months, initializing all months with default values
        $months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        $data = [];
        // Initialize data array with all months and default values
        foreach ($months as $month) {
            $data[] = [
                'month' => $month,
                'direct' => 0, // Assuming direct cost is not fetched from the database
                'indirect' => 0 // Initialize with 0 cost
            ];
        }

        // Populate data array with fetched indirect cost data
        foreach ($indirectCostData as $entry) {
            foreach ($data as &$monthData) {
                if ($monthData['month'] === $entry['month']) {
                    $monthData['indirect'] = (float)$entry['cost'];
                    break;
                }
            }
        }

        //Populate data array with fetched direct cost data
        foreach ($directCostData as $entry) {
            foreach ($data as &$monthData) {
                if ($monthData['month'] === $entry['month']) {
                    $monthData['direct'] = (float)$entry['cost'];
                    break;
                }
            }
        }
        $detail['budgetOverview'] = $data;
        $detail['incomeChartData'] = $incomeChartData;

        // Emit event to pass data to JavaScript
        $this->emit('indirectCostDataReceived', $detail);
        $this->emit('recallSelect2');
    }

    private function getDirectCost($year)
    {
        $directMonthly = DB::table('projects')
            ->where('status', 1)
            ->join('sub_project_data', 'projects.id', '=', 'sub_project_data.project_id')
            ->where('sub_project_data.year', $year)
            ->where('sub_project_data.year', $year)
            ->groupBy(DB::raw('MONTH(sub_project_data.updated_at)'))
            ->orderBy(DB::raw('MONTH(sub_project_data.updated_at)'))
            ->selectRaw('MONTH(sub_project_data.updated_at) as month, SUM(sub_project_data.total_approval_budget) as total_budget')
            ->pluck('total_budget', 'month')
            ->toArray();

        $expectedCollection = [];
        foreach ($directMonthly as $month => $cost) {
            $formattedMonth = date("M", mktime(0, 0, 0, $month, 1));
            $expectedCollection[] = [
                "month" => $formattedMonth,
                "cost" => $cost
            ];
        }
        return $expectedCollection;
    }

    private function getIndirectCost($year)
    {
        return IndirectExpensesCalculation::where('year', $year)
            ->orderBy('updated_at')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->updated_at->format('M'), // Format month as 'Jan', 'Feb', etc.
                    'cost' => $item->total_approved_cost
                ];
            });
    }

    private function getTotalBudgets($year)
    {
        $budget['totalAnnualBudget'] = Project::where('status', 1)
            ->join('sub_project_data', 'projects.id', '=', 'sub_project_data.project_id')
            ->where('sub_project_data.year', $year)
            ->sum('sub_project_data.total_approval_budget');
        $budget['totalProjectedBudget'] = Project::where('status', 1)
            ->join('sub_project_data', 'projects.id', '=', 'sub_project_data.project_id')
            ->where('sub_project_data.year', $year)
            ->sum('sub_project_data.actual_expenses_to_date');
        return $budget;
    }

    private function getEmployeeData($year)
    {
        $data = User::with(['userprofile', 'subProjectData' => function ($query) use ($year) {
            $query->where('year', $year)
                ->where('project_hierarchy_id', 1)
                ->with(['project.project']);
        }])
            ->whereHas('roles', function ($roles) {
                $roles->where('role_id', '=', RoleConstant::EMPLOYEE);
            })
            ->orderBy('name')
            ->get();

        // Sort subProjectData for each user by the percentage of used budget in descending order
        return $data->each(function ($user) use ($year) {
            $user->subProjectData = $user->subProjectData->sortByDesc(function ($subProject) use ($year) {
                return getEmployeeProjectPercentage($subProject->project_id, $subProject->employee_id, $year, true, false);
            });
        });
    }

    private function getDonorData($year)
    {
        $donorsProjects = User::with(['userprofile'])->whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::DONOR);
            }
        )->whereHas('projects', function ($query) use ($year) {
            $query->whereYear('project_duration_from', '<=', $year)
                ->whereYear('project_duration_to', '>=', $year);
        })->get()->map(function ($donor) use ($year) {
            $donor->total_budget = $donor->projects->sum(function ($project) use ($year) {
                return calCulateprojectMatrix($project->id, $year, 'approval_budget', NULL);
            });
            return $donor;
        });
        return $donorsProjects;
    }

    private function prepareIncomeChartData($donorsProjects)
    {
        $totalBudgetSum = $donorsProjects->sum('total_budget');
        $chartData = $donorsProjects->map(function ($donor) {
            return [
                'name' => $donor->name,
                'y' => $donor->total_budget,
            ];
        })->values();
        return [
            'chartData' => $chartData,
            'totalBudgetSum' => $totalBudgetSum,
        ];
    }

    public function saveReportData($data)
    {
        if (isset($data['monthly_amount'])) {
            $data['monthly_amount'] = removeDutchFormat($data['monthly_amount']);
        }
        $data['projected_budget'] = removeDutchFormat($data['projected_budget']);
        if (isset($data['is_other_direct']) && $data['is_other_direct']) {
            $data['other_direct_expense'] = $data['employee_id'];
            $matchThese = ['other_direct_expense' => $data['employee_id'], 'year' => $data['year']];
            $data['employee_id'] = '';
        } else if (isset($data['is_indirect']) && $data['is_indirect']) {
            $data['indirect_expense'] = $data['employee_id'];
            $matchThese = ['indirect_expense' => $data['employee_id'], 'year' => $data['year']];
            $data['employee_id'] = '';
        } else {
            $matchThese = ['employee_id' => $data['employee_id'], 'year' => $data['year']];
        }
        AnnualReport::updateOrCreate($matchThese, $data);
        $this->emit('reinitializeSelect2');
        $this->emit('render');
        $this->emit('budgetOverview');
        $this->emit('swal:alert', [
            'title'       => 'Success!',
            'text'        => 'Data updated successfully!',
            'icon'        => 'success',
            'redirectUrl' => '',
            'status'      => 'success'
        ]);
    }

    public function updateProjectUnits($data)
    {
        $this->setRepository();
        $percentage = 100;
        $subProjectId = (isset($data['sub_project_id']) && $data['sub_project_id'] != '') ? $data['sub_project_id'] : NULL;
        $year = isset($data['year']) ? $data['year'] : $this->year;
        $total_approval_budget = 0;
        $remaining_balance = 0;
        $expenses = 0;
        $projectId = $data['project_id'];
        $remove_indirect_cost_percentage = removeDutchFormatPercentage($data['indirect_cost_percentage']);
        $record = SubProjectData::where(['id' => $data['sub_project_data_id']])->first();

        $subProjectData = $this->subProjectDataRepository->whereUpdate(['id' => $data['sub_project_data_id'], 'year' => $year], [
            'sub_project_id'            => $subProjectId,
            'units'                     => removeDutchFormat($data['unit']),
            'unit_costs'                => $record->revised_units == 0 ? $data['unit_costs'] : $record->unit_costs,
            'remaining_balance'         => $record->revised_units == 0 ? $data['remaining_balance'] : $record->remaining_balance,
            'total_approval_budget'     => $record->revised_annual == 0 ? $data['total_approval_budget'] : $record->total_approval_budget,
            'revised_annual'     => $record->revised_annual != 0 ? $data['total_approval_budget'] : 0,
            'revised_units'     => $record->revised_units != 0 ? removeDutchFormat($data['unit']) : 0,
            'revised_unit_amount'     => $record->revised_units != 0 ? $data['unit_costs'] : 0,
            'percentage'                => $data['indirect_cost_percentage'] ?? 0
        ]);
        saveActivitiesExpensesInExact($subProjectData);
        $sub_project_data = $this->subProjectDataRepository->where(['sub_project_id' => $subProjectId, 'project_id' => $projectId, 'year' => $year])->get();
        foreach ($sub_project_data as $key => $sub_project_info) {
            $total_approval_budget += $sub_project_info->total_approval_budget;
            $remaining_balance += $sub_project_info->remaining_balance;
            $expenses += $sub_project_info->actual_expenses_to_date;
        }
        if ($subProjectId == NULL) {
            $this->projectDetailRepository->whereUpdate(['project_id' => $projectId, 'year' => $year], [
                'approved_budget'    =>  $total_approval_budget + ((floatval($total_approval_budget) * floatval($remove_indirect_cost_percentage)) / floatval($percentage)),
                'remaining_budget'   =>  $remaining_balance + ((floatval($remaining_balance) * floatval($remove_indirect_cost_percentage)) / floatval($percentage)),
                'expenses'           =>  $expenses + ((floatval($expenses) * floatval($remove_indirect_cost_percentage)) / floatval($percentage)),
            ]);
        } else {
            $this->projectDetailRepository->whereUpdate(['project_id' => $projectId, 'sub_project_id' => $subProjectId, 'year' => $year], [
                'approved_budget'    =>  $total_approval_budget + ((floatval($total_approval_budget) * floatval($remove_indirect_cost_percentage)) / floatval($percentage)),
                'remaining_budget'   =>  $remaining_balance + ((floatval($remaining_balance) * floatval($remove_indirect_cost_percentage)) / floatval($percentage)),
                'expenses'           =>  $expenses + ((floatval($expenses) * floatval($remove_indirect_cost_percentage)) / floatval($percentage)),
            ]);
        }
        AnnualReport::where('year', $this->year)->update(['updated_at' => now()]);
        $this->emit('hideRows', $data['recordId']);
        $this->emit('reinitializeSelect2');
    }


    private function getOtherDirectExpenses($year)
    {
        $data = SubProjectData::whereIn('project_hierarchy_id', [2, 3, 4, 5])
            ->where('year', $year)
            ->with(['project.project'], 'projectLookUps')
            ->orderBy('employee_id')
            ->get();

        // Organize data as per your requirement
        $result = [];
        foreach ($data as $subProjectData) {
            if ($subProjectData) {
                $directExpense = $subProjectData->project_hierarchy_id;
            }
            $project = $subProjectData->project;
            $subProject = $subProjectData->subProject;

            // Initialize direct expense if not present in result array
            if (!isset($result[$directExpense])) {
                $result[$directExpense] = [];
            }

            // Initialize project array if not present under direct expense
            if (!isset($result[$directExpense][$project->project_name])) {
                $result[$directExpense][$project->project_name] = [];
                // Add project_id alongside project_name
                $result[$directExpense][$project->project_name]['project_id'] = $project->id;
            }

            // Add sub-project data if sub_project_id is not null
            if ($subProjectData->sub_project_id !== null) {
                $result[$directExpense][$project->project_name][$subProject->sub_project_name][] = $subProjectData;
            } else {
                $result[$directExpense][$project->project_name]['data'] = $subProjectData;
            }
        }
        return $result;
    }

    private function getLineItemOtherDirectExpenses($year)
    {
        $result = OtherDirectExpense::orderBy('name')->get();
        foreach ($result as $key => $value) {
            $value->projects = SubProjectData::where('project_hierarchy_id', 6)->where('employee_id', $value->id)
                ->where('year', $year)
                ->with(['project.project'], 'projectLookUps')
                ->orderBy('employee_id')
                ->sum(DB::raw('CASE WHEN revised_annual = 0 THEN total_approval_budget ELSE revised_annual END'));;
        }
        return $result;
    }

    private function getLineItemsOtherDirectData($year)
    {
        $data = User::with(['userprofile'])->whereHas(
            'roles',
            function ($roles) {
                $roles->whereIn('role_id', [RoleConstant::CONSULTANT]);
            }
        )->with(['subProjectData' => function ($query) use ($year) {
            $query->where('year', $year);
        }, 'subProjectData.project.project'])
            ->whereHas('subProjectData', function ($query) use ($year) {
                $query->where('year', $year);
            })->orderBy('name')->get();
        return $data;
    }

    public function getYear($year)
    {
        $this->year = $year;
        $this->emit('render');
        $this->emit('budgetOverview');
        $this->emit('yearLoaded');
    }

    public function getProject($year)
    {
        $allProjects = Project::where('status', 1)->with(['projectDetail' => function ($query) use ($year) {
            $query->where('year', $year);
            $query->whereNotNull('percentage');
        }])->get();
        return $allProjects;
    }

    public function toggleRowState($employeeId)
    {
        $this->emit('rowToggled', $employeeId);
    }
}
