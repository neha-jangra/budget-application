<?php

use Illuminate\Support\Facades\Route;
use App\Models\{SubProjectData, SubProject, OtherDirectExpensesCalculation, IndirectExpensesCalculation, Project, ProjectDetail, LookUp, User, LineItemDailyRate, OtherDirectExpense, AnnualReport};
use Carbon\Carbon;
use App\Constants\RoleConstant;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\DB;

function sixdigitOTP()
{
    return rand(pow(10, 4 - 1), pow(10, 4) - 1);
}

function twoDateDifference($start_date, $end_date)
{
    $date1 = new DateTime($start_date);
    $date2 = new DateTime($end_date);

    // Ensure $date1 is always earlier than $date2
    if ($date1 > $date2) {
        $temp = $date1;
        $date1 = $date2;
        $date2 = $temp;
    }

    $interval = $date1->diff($date2);

    $years = $interval->y;
    $months = $interval->m;
    $days = $interval->d;

    // Adjust for the end-of-month cases
    if ($days > 0) {
        $months += 1;
        $days = 0;
    }

    if ($months >= 12) {
        $years += intdiv($months, 12);
        $months = $months % 12;
    }

    $result = [];

    if ($years) {
        $result[] = $years . ' ' . ($years > 1 ? 'Years' : 'Year');
    }

    if ($months) {
        $result[] = $months . ' ' . ($months > 1 ? 'Months' : 'Month');
    }

    if ($days) {
        $result[] = $days . ' ' . ($days > 1 ? 'Days' : 'Day');
    }

    if (empty($result)) {
        return '0 Days';
    }

    return implode(' ', $result);
}


function dateFormat($date, $format)
{
    if ($format == 'mdy') {
        return date('F d, Y', strtotime($date));
    } else if ($format == 'previous_month') {
        return date('M', strtotime(date('Y-m') . " -1 month"));
    } else if ($format == 'd-m-y') {
        return date('d-m-Y', strtotime($date));
    } else if ($format == 'next_month') {
        return date('M', strtotime(date('Y-m') . " +1 month"));
    } elseif ($format == 'dmy') {
        return date('d F Y', strtotime($date));
    }
}

function Breadcrumb($id = NULL)
{
    $breadcrumbs = [];

    $route = currentRoute();

    // Add additional breadcrumbs based on the route
    if ($route === 'project.project') {

        $breadcrumbs[] = [

            'title' => 'Projects',

            'url' => '',
        ];
    } elseif ($route === 'project/{project}') {
        $breadcrumbs[] = [

            'title' => 'Project',

            'url' => route('project.index'),
        ];
        $breadcrumbs[] = [

            'title' => 'Core support',

            'url' => '',
        ];
    } elseif ($route === 'donor.donor') {


        $breadcrumbs[] = [

            'title' => 'Donors',

            'url' => '',
        ];
    } elseif ($route === 'line-item' || $route === 'lineitem.lineitem') {



        $breadcrumbs[] = [

            'title' => 'Line Items',

            'url' => '',
        ];
    } elseif ($route === 'user' || $route === 'user.user') {


        $breadcrumbs[] = [

            'title' => 'Users',

            'url' => '',
        ];
    } elseif ($route === 'rolemanagement' || $route === 'rolemanagement.rolemanagement' || $route == 'role-management') {

        $breadcrumbs[] = [

            'title' => 'Role Management',

            'url' => '',
        ];
    } elseif ($route === 'reports') {

        $breadcrumbs[] = [

            'title' => 'Reports',

            'url' => '',
        ];
    } elseif ($route === 'indirect-costs-budget') {

        $breadcrumbs[] = [

            'title' => 'Indirect Costs Budget',

            'url' => '',
        ];
    } elseif ($route === 'settings') {

        $breadcrumbs[] = [

            'title' => 'Settings',

            'url' => '',
        ];
    }

    return $breadcrumbs;
}

function currentRoute()
{
    // Get the current route
    $route = Route::current();

    $parameters = $route->parameters();

    if (isset($parameters['name'])) {
        return $parameters['name'];
    } else {
        return  $route->uri;
    }
}

function calCulateprojectMatrix($project_id, $tabYear = NULL, $_type = NULL, $sub_project_id = NULL)
{

    // $_project_detail = new ProjectDetai                            l();
    // $_project_calculation_matrix = $_project_detail->calCulateprojectMatrix($project_id, $tabYear, $_type, $sub_project_id);
    // return ($_project_calculation_matrix == null) ? 0 : round($_project_calculation_matrix, 3);
    // Get the project budget and ensure it's treated as a float
    $projectBudget = (float) calCulateprojectBudget(null, $project_id, $tabYear, $_type, $sub_project_id);
    // Calculate the indirect cost without revision and ensure it's treated as a float
    $indirectCost = (float) calculateIndirectCostWithoutRevision($projectBudget, $project_id, $tabYear, $sub_project_id);
    // Return the sum of both values
    return $projectBudget + $indirectCost;
}


function calCulateprojectBudget($_segment_id, $project_id, $tabYear, $_type = NULL,  $sub_project_id = NULL)
{
    $_sub_project    = new SubProjectData();
    return $_sub_project->calCulateprojectBudget($_segment_id, $project_id, $tabYear, $_type, $sub_project_id);
}

function calculatePreviousYearsExpensesBudgets($project_id, $isPrevous=false)
{
    // Fetch yearly sums and unique percentages
    if ($isPrevous) {
        $yearlyData = SubProjectData::where('project_id', $project_id)
            ->where('year', '<', date('Y'))
            ->selectRaw('year, SUM(actual_expenses_to_date) as yearly_expenses, 
                (SELECT percentage FROM sub_project_data spd 
                WHERE spd.project_id = sub_project_data.project_id 
                AND spd.year = sub_project_data.year 
                ORDER BY spd.id ASC LIMIT 1) as unique_percentage')
            ->groupBy('year')
            ->get();
    } else {
        $yearlyData = SubProjectData::where('project_id', $project_id)
            ->selectRaw('year, SUM(actual_expenses_to_date) as yearly_expenses, 
                (SELECT percentage FROM sub_project_data spd 
                WHERE spd.project_id = sub_project_data.project_id 
                AND spd.year = sub_project_data.year 
                ORDER BY spd.id ASC LIMIT 1) as unique_percentage')
            ->groupBy('year')
            ->get();
    }


    // Initialize the total calculated expenses
    $totalCalculatedExpenses = 0;
    foreach ($yearlyData as $data) {
        // Convert percentage to decimal
        $percentage = (float)$data->unique_percentage / 100;
        // Calculate the amount for this year's total expenses using the percentage
        $calculatedExpense = (float)$data->yearly_expenses * (float)$percentage;

        $calculatedExpense = (float)$calculatedExpense + (float)$data->yearly_expenses;

        // Add this year's calculated value to the total
        $totalCalculatedExpenses += $calculatedExpense;
    }

    // Return the total calculated expenses formatted to two decimal places
    return number_format($totalCalculatedExpenses, 2, '.', '');
}

function calculateIndirectCost($total_approved_cost, $_total_direct_cost)
{

    if ($total_approved_cost == NULL) {
        $total_approved_cost = 1;
    }

    $_minus_total = $total_approved_cost - $_total_direct_cost;

    if ($_total_direct_cost == 0 || $_total_direct_cost == NULL) {
        $_total_direct_cost = 1;
    }

    $_divided = $_minus_total / $_total_direct_cost;

    $is_divided = ($_divided == 1) ? 0 : $_divided;

    $_divide = ($is_divided) * 100;

    return number_format((float)$_divide, 2, '.', '');
}



function substrString($string)
{
    preg_match('/(?:\w+\. )?(\w+).*?(\w+)(?: \w+\.)?$/', $string, $result);

    $_first_name = isset($result[1][0]) ? $result[1][0] : '';

    $_last_name = isset($result[2][0]) ? $result[2][0] : '';

    return strtoupper($_first_name . $_last_name);
}

function currency($value)
{
    if ($value == 'usd') {
        return '$';
    } elseif ($value == 'gbp') {
        return '£';
    } elseif ($value == 'eur') {
        return '€';
    } else {
        return '€';
    }
}

function getFirstNameLastName($name)
{
    $nameParts = explode(' ', $name);

    $firstName = isset($nameParts[0]) ? $nameParts[0] : '';

    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    return array('first_name' => $firstName, 'last_name' => $lastName);
}

function indirectcost($sub_project_id, $project_id, $tabYear = null)
{
    $_sub_project    = new SubProjectData();

    return $_sub_project->indirectcost($sub_project_id, $project_id, $tabYear);
}

function projectDetailPercentage($project_id)
{
    $_sub_project    = new ProjectDetail();

    $_get_percentage = isset(($_sub_project->getPercentage($project_id))->percentage) ? ($_sub_project->getPercentage($project_id))->percentage : null;

    return ($_get_percentage) ? $_get_percentage : '';
}

function numberFormat($number, $decimals = 0)
{
    if (strpos($number, '.') != null) {
        $decimalNumbers = substr($number, strpos($number, '.'));
        $decimalNumbers = substr($decimalNumbers, 1, $decimals);
    } else {
        $decimalNumbers = 0;
        for ($i = 2; $i <= $decimals; $i++) {
            $decimalNumbers = $decimalNumbers . '0';
        }
    }
    $number = (int) $number;
    // reverse
    $number = strrev($number);
    $n = '';
    $stringlength = strlen($number);

    for ($i = 0; $i < $stringlength; $i++) {
        if ($i % 2 == 0 && $i != $stringlength - 1 && $i > 1) {
            $n = $n . $number[$i] . ',';
        } else {
            $n = $n . $number[$i];
        }
    }
    $number = $n;
    // reverse
    $number = strrev($number);
    ($decimals != 0) ? $number = $number . '.' . $decimalNumbers : $number;
    return $number;
}

function convertTosection($key)
{
    if ($key === 1) {
        return 'employee';
    } elseif ($key === 2) {

        return 'consultant';
    } elseif ($key === 3) {

        return 'sub-grantee';
    } elseif ($key === 4) {

        return 'travel';
    } elseif ($key === 5) {

        return 'meeting';
    } elseif ($key === 6) {
        return 'other direct cost';
    } else {
        return 'employee';
    }
}

function dutchCurrency($number)
{
    // Ensure the input is treated as a float
    $number = (float)$number;
    return number_format($number, 2, ',', '.');
}


function convertToSimpleNumber($formattedCurrency)
{

    $simpleNumber = str_replace(['.', ','], ['', '.'], $formattedCurrency);

    return $simpleNumber;
}

function getrolePermission($rolepermission)
{
    $rolepermissionParts = explode(',', $rolepermission);

    $role                = isset($rolepermissionParts[0]) ? $rolepermissionParts[0] : '';

    $permission          = isset($rolepermissionParts[1]) ? $rolepermissionParts[1] : '';

    return array('role' => $role, 'permission' => $permission);
}

function getPermission($permission_data)
{
    $permissions = array();

    foreach ($permission_data as $key => $permission) {
        $permissions[] = $permission->display_name;
    }

    if (!empty($permissions)) {
        return  implode(", ", $permissions);
    } else {
        return '-';
    }
}


function formatNumber($n)
{
    return preg_replace('/\B(?=(\d{3})+(?!\d))/u', '.', $n); // Change , to .
}

function netherlandformatCurrency($input, $blur = NULL)
{
    // get input value
    $input_val = $input;

    // don't validate empty input
    if ($input_val === "") {
        return $input_val;
    }

    // check for decimal
    if (strpos($input_val, ".") !== false) {
        // get position of first decimal
        $decimal_pos = strpos($input_val, ".");

        // split number by decimal point
        $left_side = substr($input_val, 0, $decimal_pos);
        $right_side = substr($input_val, $decimal_pos + 1); // Skip the decimal point

        // add dots to left side of number
        $left_side = formatNumber($left_side);

        // validate right side
        $right_side = formatNumber($right_side);

        // On blur make sure 2 numbers after decimal
        if ($blur === "blur") {
            $right_side .= "00";
        }

        // Limit decimal to only 2 digits
        $right_side = substr($right_side, 0, 2);

        // join number by ,
        $input_val = $left_side . "," . $right_side;
    } else {
        // no decimal entered
        // add dots to number
        // remove all non-digits
        $input_val = formatNumber($input_val);

        // final formatting
        if ($blur === "blur") {
            $input_val .= ",00";
        }
    }

    return $input_val;
}


function removeDutchFormat($inputNumber)
{
    $parts = explode(',', $inputNumber);

    // Replacing dots (.) with empty string only in the first part
    $firstPart = str_replace('.', '', $parts[0]);

    // If there's a second part (after the comma), append it with dot (.) back to the first part
    if (isset($parts[1])) {
        $firstPart .= '.' . $parts[1];
    }

    return $firstPart;
}



function hasPlusSign($phoneNumber)
{
    return preg_match('/^\+/', $phoneNumber) === 1;
}

function removeZeroCountry($phone_number)
{
    $_hasPlusSign = hasPlusSign($phone_number);

    if ($_hasPlusSign) {
        $phoneNumber = new PhoneNumber($phone_number);

        return  ltrim($phoneNumber->formatNational(), '0');
    } else {
        return  ltrim($phone_number, '0');
    }
}

function ducthCalculationIndirect($value1, $value2)
{
    $diff = $value1 - $value2;

    return dutchCurrency($diff);
}

function sideBarPermission($sidebar = 'budgeting')
{

    if ($sidebar == 'budgeting') {
        $_side_bar = array(
            'project',
            'donor',
            'line_item'
        );
    }
    if ($sidebar == 'managemnet') {
        $_side_bar = array(
            'user',
            'role_management'
        );
    }

    $_return_permission = false;

    foreach ($_side_bar as $key => $value) {
        if (auth()->user()->roles->flatMap->permissions->pluck('name')->contains($value)) {
            return $_return_permission = true;
        }
    }
}

function nameInitial()
{

    $_last_name = auth()->user()->userprofile->last_name;

    $_first_name = auth()->user()->userprofile->first_name;

    return ($_last_name) ?  substrString($_first_name . ' ' . $_last_name) : ucfirst(mb_substr($_first_name, 0, 1));
}

function nameInitialByName($firstName, $lastName = NULL)
{
    return ($lastName) ?  substrString($firstName . ' ' . $lastName) : ucfirst(mb_substr($firstName, 0, 1));
}

function commaSeprated($_look_up_id, $_subb_project_id)
{
    $look_up_id = $_look_up_id - 1;

    if ($_subb_project_id == NULL) {
        return $look_up_id;
    } else {
        return $look_up_id . ',' . $_subb_project_id;
    }
}

function collapsedRow($collapse_last_tab, $sub_project_id = NULL, $key = NULL)
{
    if ($sub_project_id == NULL) {
        return $collapse_last_tab;
    } else {
        $target = $key . ',' . $sub_project_id;

        return $collapse_last_tab === $target;
    }
}

function collapsedRowAll($collapse_last_tab, $sub_project_id = NULL, $key = NULL)
{
    if ($sub_project_id == NULL) {
        $target = $key + 1;

        return $collapse_last_tab == $target;
    } else {
        $target = $key . ',' . $sub_project_id;

        return $collapse_last_tab === $target;
    }
}

function removeDutchFormatPercentage($inputNumber)
{

    $parts = explode('.', $inputNumber);

    return str_replace('.', '', $parts[0]);
}

function calculatePrice($data)
{
    $units = isset($data['units']) ? (float)$data['units'] : 0;
    $unitCost = isset($data['cost_per_unit']) ? (float)$data['cost_per_unit'] : 0;
    $actualExpense = isset($data['actual_cost_till_date']) ? (float)$data['actual_cost_till_date'] : 0;
    $approvedCost = $units * $unitCost;
    $remainingCost = $approvedCost - $actualExpense;
    $data['total_approved_cost'] = $approvedCost;
    $data['remaining_cost'] = $remainingCost;
    return $data;
}

function getOtherDirectByCategory($otherDirectId, $catId, $year)
{
    return OtherDirectExpensesCalculation::where('other_direct_expense_id', $otherDirectId)->where('indirect_expense_category_id', $catId)->where('year', $year)->first();
}

function calculateSumODE($type, $catId, $year = null)
{
    $otherDirectExpenseCal = new OtherDirectExpensesCalculation();
    return $otherDirectExpenseCal->calculateSum($type, $catId, $year);
}

function calculateSumByOtherDirect($type, $catId, $year = null)
{
    $otherDirectExpenseCal = new OtherDirectExpensesCalculation();
    return $otherDirectExpenseCal->calculateSumByOtherDirect($type, $catId, $year);
}

function calculateSumODByAllCat($type, $year = null)
{
    $otherDirectExpenseCal = new OtherDirectExpensesCalculation();
    return $otherDirectExpenseCal->calculateSumODByAllCat($type, $year);
}

function calculateSumIEByCat($type, $catId, $year = null)
{
    $indirectExpenseCal = new IndirectExpensesCalculation();
    return $indirectExpenseCal->calculateSumByCat($type, $catId, $year);
}

function calculateSumIEByAllCat($type, $year = null)
{
    $indirectExpenseCal = new IndirectExpensesCalculation();
    return $indirectExpenseCal->calculateSumByAllCat($type, $year);
}

function calculateSumIE($type, $empId, $year = null)
{
    $indirectExpenseCal = new IndirectExpensesCalculation();
    return $indirectExpenseCal->calculateSumByEmployee($type, $empId, $year);
}

function calculateYears($startDate, $endDate)
{
    $start = Carbon::parse($startDate);
    $end = Carbon::parse($endDate);
    $currentYear = date('Y');

    $yearsInfo = [];

    // Loop over each year in the date range
    for ($year = $start->year; $year <= $end->year; $year++) {
        $isCurrentYear = ($year == $currentYear);
        $isFutureYear = ($year > $currentYear);

        $yearStartDate = ($year == $start->year) ? $start->format('M, Y') : 'Jan, ' . $year;
        $yearEndDate = ($year == $end->year) ? $end->format('M, Y') : 'Dec, ' . $year;
        $fromMonth = ($year == $start->year) ? $start->format('M') : 'Jan';
        $endMonth = ($year == $end->year) ? $end->format('M') : 'Dec';

        $yearsInfo[] = [
            'year' => $year,
            'isCurrentYear' => $isCurrentYear,
            'isFutureYear' => $isFutureYear,
            'startDate' => $yearStartDate,
            'endDate' => $yearEndDate,
            'fromMonth' => $fromMonth,
            'endMonth' => $endMonth,
        ];
    }

    return $yearsInfo;
}
function getProjectsTabsData($year, $projectId)
{
    $oldSubjectTillDate = config('env.STATIC_SUBPROJECT_DATE');
    $data  = SubProject::with(['subProjectData.user'])->with('subProjectData', function ($q) use ($year) {
        $q->where('year', $year);
    })->where('project_id', $projectId)->where(function ($query) use ($year, $oldSubjectTillDate) {
        $query->where('year', $year)
            ->orWhereDate('created_at', '<', $oldSubjectTillDate);
    })->get();

    foreach ($data as $key => $project_segment) {
        $project_segment->project_hierarchy = LookUp::with(['projecthierarchdata' => function ($q) use ($project_segment, $year) {
            $q->where(['year' => $year])->where(['sub_project_id' => $project_segment->id]);
        }])->get();

        foreach ($project_segment->project_hierarchy as $key => $project_segment) {
            if ($project_segment->id  == 1 || $project_segment->id  == 2 || $project_segment->id  == 3 || $project_segment->id  == 6) {
                $role_id  = NULL;
                if ($project_segment->id  == 1) {
                    $role_id = 3;
                } elseif ($project_segment->id  == 2) {
                    $role_id = 5;
                } elseif ($project_segment->id  == 3) {
                    $role_id = 4;
                }
                if ($project_segment->id != 6) {
                    $project_segment->donors  =  User::whereHas(
                        'roles',
                        function ($roles) use ($role_id) {
                            $roles->where(['role_id' => $role_id])->whereNotIn('role_id', [RoleConstant::DONOR, RoleConstant::ADMIN]);
                        }
                    )->orderBy('name')->get();
                } else {
                    if (!empty($project_segment->sub_project_id)) {
                        $selectedOtherExpenses = SubProjectData::where(['project_id' => $project_segment->project_id, 'sub_project_id' => $project_segment->sub_project_id, 'year' => $year, 'project_hierarchy_id' => 6])->pluck('employee_id')->toArray();
                    } else {
                        $selectedOtherExpenses = SubProjectData::where(['project_id' => $project_segment->project_id, 'year' => $year, 'project_hierarchy_id' => 6])->pluck('employee_id')->toArray();
                    }
                    $project_segment->donors = OtherDirectExpense::whereNotIn('id', $selectedOtherExpenses)
                        ->where('is_project', 1)
                        ->orderBy('name')
                        ->get();
                }
            } else {
                $project_segment->donors  = [];
            }
        }
    }
    if (count($data) == 0) {
        $data = LookUp::with(['projecthierarchdata' => function ($q) use ($year, $projectId) {
            $q->where(['project_id' => $projectId])
                ->where(['year' => $year]);
        }])->where(['look_up_field' => 'project_segment'])->get();
        foreach ($data as $key => $project_segment) {
            if ($project_segment->id  == 1 || $project_segment->id  == 2 || $project_segment->id  == 3 || $project_segment->id  == 6) {
                $role_id = NULL;
                if ($project_segment->id  == 1) {
                    $role_id = 3;
                } elseif ($project_segment->id  == 2) {
                    $role_id = 5;
                } elseif ($project_segment->id  == 3) {
                    $role_id = 4;
                }

                if ($project_segment->id != 6) {
                    $project_segment->donors  =  User::whereHas(
                        'roles',
                        function ($roles) use ($role_id) {
                            $roles->where(['role_id' => $role_id])->whereNotIn('role_id', [RoleConstant::DONOR, RoleConstant::ADMIN]);
                        }
                    )->orderBy('name')->get();
                } else {
                    if (!empty($project_segment->sub_project_id)) {
                        $selectedOtherExpenses = SubProjectData::where(['project_id' => $project_segment->project_id, 'sub_project_id' => $project_segment->sub_project_id, 'year' => $year, 'project_hierarchy_id' => 6])->pluck('employee_id')->toArray();
                    } else {
                        $selectedOtherExpenses = SubProjectData::where(['project_id' => $project_segment->project_id, 'year' => $year, 'project_hierarchy_id' => 6])->pluck('employee_id')->toArray();
                    }
                    $project_segment->donors = OtherDirectExpense::whereNotIn('id', $selectedOtherExpenses)
                        ->where('is_project', 1)
                        ->orderBy('name')
                        ->get();
                }
            } else {
                $project_segment->donors  = [];
            }
        }
    }
    return $data;
}

function allProjects($year, $projectId)
{
    $data = LookUp::with(['projecthierarchdata' => function ($q) use ($year, $projectId) {
        $q->where(['project_id' => $projectId])
            ->where(['year' => $year]);
    }])->get();
    return $data->each(function ($lookup) {
        $subProjectDataCollection = $lookup->projecthierarchdata;
        $groupedData = [];
        foreach ($subProjectDataCollection as $sub_key =>  $subProjectData) {

            $employeeId = $subProjectData->employee_id;
            if (!isset($groupedData[$employeeId])) {
                $groupedData[$employeeId] = [];
                $groupedData[$employeeId]['unit_costs_total'] = 0;
                $groupedData[$employeeId]['units_total'] = 0;
                $groupedData[$employeeId]['total_approval_budget'] = 0;
                $groupedData[$employeeId]['actual_expenses_to_date'] = 0;
                $groupedData[$employeeId]['remaining_balance'] = 0;
                $groupedData[$employeeId]['revised_units'] = 0;
                $groupedData[$employeeId]['revised_unit_amount'] = 0;
                $groupedData[$employeeId]['revised_new_budget'] = 0;
                $groupedData[$employeeId]['revised_annual'] = 0;
            }

            $groupedData[$employeeId]['unit_costs_total'] += (float) $subProjectData->unit_costs;
            $groupedData[$employeeId]['units_total'] += (float) $subProjectData->units;
            $groupedData[$employeeId]['id'] =  $subProjectData->id;
            $groupedData[$employeeId]['project_id'] =  $subProjectData->project_id;
            $groupedData[$employeeId]['employee_id'] =  $subProjectData->employee_id;
            if ($subProjectData->project_hierarchy_id == 6) {
                $groupedData[$employeeId]['employee_name'] =   OtherDirectExpense::where('id', $groupedData[$employeeId]['employee_id'])->value('name');
            } else {
                $groupedData[$employeeId]['employee_name'] =  isset($subProjectData->user->name) ? $subProjectData->user->name : $subProjectData->employee_id;
            }

            $groupedData[$employeeId]['note'] =  $subProjectData->note;
            $groupedData[$employeeId]['total_approval_budget'] += (float) $subProjectData->total_approval_budget;
            $groupedData[$employeeId]['actual_expenses_to_date'] += (float) $subProjectData->actual_expenses_to_date;
            $groupedData[$employeeId]['remaining_balance'] += (float) $subProjectData->remaining_balance;
            $groupedData[$employeeId]['key'] = $sub_key;
            $groupedData[$employeeId]['revised_units'] += (float) $subProjectData->revised_units;
            $groupedData[$employeeId]['revised_unit_amount'] += (float) $subProjectData->revised_unit_amount;
            $groupedData[$employeeId]['revised_new_budget'] += (float) $subProjectData->revised_new_budget;
            $groupedData[$employeeId]['revised_annual'] += (float) $subProjectData->revised_annual;
        }

        $lookup->setRelation('projecthierarchdata', collect($groupedData));
    });
}



function getTabProjects($projectId, $year)
{
    $oldSubjectTillDate = config('env.STATIC_SUBPROJECT_DATE');
    return Project::with(['project' => function ($q) use ($year, $oldSubjectTillDate) {
        $q->where('sub_project_name', '!=', null)
            ->where(function ($query) use ($oldSubjectTillDate, $year) {
                $query->whereDate('created_at', '<', $oldSubjectTillDate)
                    ->orWhere('year', $year);
            });
    }])->where('id', $projectId)->first();
}


function getExpenseName($employeeId)
{
    return OtherDirectExpense::where('id', $employeeId)->value('name');
}

function getLookup($year, $projectId)
{
    $data = LookUp::with(['projecthierarchdata' => function ($q) use ($year, $projectId) {
        $q->where(['project_id' => $projectId])
            ->where(['year' => $year]);
    }])->where(['look_up_field' => 'project_segment'])->get();
    foreach ($data as $key => $project_segment) {
        if ($project_segment->id  == 1 || $project_segment->id  == 2 || $project_segment->id  == 3) {
            $role_id     = NULL;
            if ($project_segment->id  == 1) {
                $role_id = 3;
            } elseif ($project_segment->id  == 2) {
                $role_id = 5;
            } elseif ($project_segment->id  == 3) {
                $role_id = 4;
            }
            if ($project_segment->id != 6) {
                $project_segment->donors  =  User::whereHas(
                    'roles',
                    function ($roles) use ($role_id) {
                        $roles->where(['role_id' => $role_id])->whereNotIn('role_id', [RoleConstant::DONOR, RoleConstant::ADMIN]);
                    }
                )->orderBy('name')->get();
            } else {
                if (!empty($project_segment->sub_project_id)) {
                    $selectedOtherExpenses = SubProjectData::where(['project_id' => $project_segment->project_id, 'sub_project_id' => $project_segment->sub_project_id, 'year' => $year, 'project_hierarchy_id' => 6])->pluck('employee_id')->toArray();
                } else {
                    $selectedOtherExpenses = SubProjectData::where(['project_id' => $project_segment->project_id, 'year' => $year, 'project_hierarchy_id' => 6])->pluck('employee_id')->toArray();
                }
                $project_segment->donors =
                    OtherDirectExpense::whereNotIn('id', $selectedOtherExpenses)
                    ->where('is_project', 1)
                    ->orderBy('name')
                    ->get();
            }
        } else {
            $project_segment->donors  = [];
        }
    }
    return $data;
}


function getDailyRateForYear($employeeId, $year, $user)
{
    // Check if any record exists for the given user
    $employeeQuery = LineItemDailyRate::where('user_id', $employeeId);

    if ($employeeQuery->exists()) {
        $maxYear = $employeeQuery->whereYear('rate_applicable_from', '<=', $year)
            ->max(DB::raw('YEAR(rate_applicable_from)'));

        $employeeResult = $employeeQuery->whereYear('rate_applicable_from', $maxYear)
            ->orderByDesc('updated_at')
            ->first();

        return $employeeResult->rate ?? 0;
    }

    // Return the user's profile rate if no entry is available
    return $user->userProfile->rate ?? 0;
}



function calculateAverageDailyRatePast($employeeId, $currentRate)
{
    $currentDate = Carbon::now();
    // Retrieve the daily rate records for the employee
    $dailyRates = LineItemDailyRate::where('user_id', $employeeId)->whereYear('rate_applicable_from', '<=', date('Y') - 1)->orderBy('rate_applicable_from', 'desc')
        ->get();
    $dailyRates = $dailyRates->sortByDesc('id');
    if (count($dailyRates) > 0) {
        $dailyRates = LineItemDailyRate::where('user_id', $employeeId)->where('rate_applicable_from', '<=', $currentDate)->orderBy('rate_applicable_from', 'desc')
            ->get();
        $dailyRates = $dailyRates->sortByDesc('id');
    }
    return averageRender($dailyRates, $currentRate);
}


function calculateAverageDailyRate($employeeId, $currentRate)
{
    $currentDate = Carbon::now();
    // Retrieve the daily rate records for the employee
    $dailyRates = LineItemDailyRate::where('user_id', $employeeId)
        ->where('rate_applicable_from', '<=', $currentDate)
        ->orderByDesc('rate_applicable_from')
        ->get();
    return averageRender($dailyRates, $currentRate);
}

function averageRender($dailyRates, $currentRate)
{
    $totalRate = 0;
    $totalDays = 0;
    $previousTimestamp = null;

    // Calculate the weighted sum of rates
    foreach ($dailyRates as $rate) {
        // If this is not the first rate, calculate the duration based on timestamps
        if ($previousTimestamp !== null) {
            $days = $rate->rate_applicable_from->diffInHours($previousTimestamp) / 24; // Assuming rates change hourly
            $totalRate += $rate->rate * $days;
            $totalDays += $days;
        }
        $previousTimestamp = $rate->rate_applicable_from;
    }

    // Calculate the average daily rate
    $averageRate = $totalDays > 0 ? $totalRate / $totalDays : 0;

    // Round to 2 decimal places if there are decimal values
    $averageRate = round($averageRate, 2);
    // Handle potential rounding errors
    if (abs($averageRate - 0) < 0.001) {
        return $currentRate;
    }
    return $averageRate;
}

function getPercentage($projectId, $tabYear, $subProjectId = NULL)
{
    // Retrieve the detail for the given project ID
    if ($subProjectId == null) {
        $detail = SubProjectData::where('project_id', $projectId)->where('year', $tabYear)->first();
    } else {
        $detail = SubProjectData::where('sub_project_id', $subProjectId)->where('year', $tabYear)->first();
    }
    $percentage = NULL;
    if ($detail) {
        $percentage = $detail->percentage;
    }
    return $percentage;
}

function calculateIndirectCostWithoutRevision($amount, $projectId, $tabYear, $subProjectId = NULL)
{
    $project = Project::where('id', $projectId)->first();
    $startDate = $project->current_budget_timeline_from;
    $start = Carbon::parse($startDate);

    // If $tabYear is null, use the current year and past year
    if (is_null($tabYear)) {
        $currentYear = date('Y');
        $pastYear = $currentYear - (int)$start->year;
        $conditionYear = [$pastYear, $currentYear]; // Between past and current year
    } else {
        $conditionYear = [$tabYear, $tabYear]; // Use the provided year
    }
    // Retrieve the detail for the given project ID
    if ($subProjectId == null) {
        $detail = SubProjectData::where('project_id', $projectId)->whereBetween('year', $conditionYear)->first();
    } else {
        $detail = SubProjectData::where('sub_project_id', $subProjectId)->whereBetween('year', $conditionYear)->first();
    }

    $percentage = NULL;
    if ($detail) {
        $percentage = $detail->percentage;
    }
    // Ensure percentage is a valid number
    if ($percentage === NULL || !is_numeric($percentage)) {
        // Handle the case where percentage is not available or valid
        return '';
    }

    // Convert percentage to decimal form
    $decimalPercentage = $percentage / 100;

    // Calculate the percentage of the total approved cost
    $calculatedPercentage = $amount * $decimalPercentage;

    // Return the result, formatted to two decimal places
    return number_format($calculatedPercentage, 2, '.', '');
}


function calculateTotalEstimateCost($projectId, $year = null, $type = null, $subProjectId = null)
{

    // Get the project budget and ensure it's treated as a float
    $projectBudget = (float) calCulateprojectBudget(null, $projectId, $year, $type, $subProjectId);

    // Calculate the indirect cost without revision and ensure it's treated as a float
    $indirectCost = (float) calculateIndirectCostWithoutRevision($projectBudget, $projectId, $year, $subProjectId);

    // Return the sum of both values
    return $projectBudget + $indirectCost;
}


function updateLineItem($role, $existingUser, $user)
{
    if ($role && !$existingUser->roles()->where('roles.id', $role->id)->exists()) {
        $existingUser->roles()->attach($role);
        SubProjectData::where('employee_id', $user->id)->where('project_hierarchy_id', '!=', 6)->update(['employee_id' => $existingUser->id]);
        AnnualReport::where('employee_id', $user->id)->where('is_other_direct', 0)->update(['employee_id' => $existingUser->id]);
        AnnualReport::where('employee_id', $user->id)->where('is_other_direct', 0)->update(['employee_id' => $existingUser->id]);
        IndirectExpensesCalculation::where('employee_id', $user->id)->update(['employee_id' => $existingUser->id]);
        // Detach all roles from the user to be deleted
        $user->roles()->detach();
        $user->userprofile()->delete();
        User::where('id', $user->id)->delete();
    }
}

function validateEmail($email, $existingUser)
{
    $excludedRoles = [
        RoleConstant::DONOR,
        RoleConstant::EMPLOYEE,
        RoleConstant::CONSULTANT,
        RoleConstant::SUBGRANTEE,
    ];
    if ($existingUser) {
        $roles = $existingUser->roles->pluck('id')->toArray();
        $roleMessages = [
            RoleConstant::DONOR => 'A donor with this email already exists.',
            RoleConstant::EMPLOYEE => 'An employee with this email already exists.',
            RoleConstant::CONSULTANT => 'A consultant with this email already exists.',
            RoleConstant::SUBGRANTEE => 'A sub-grantee with this email already exists.',
        ];

        foreach ($excludedRoles as $role) {
            if (in_array($role, $roles)) {
                return $roleMessages[$role];
            }
        }
    }
    return null;
}
