<?php

use App\Models\{SubProjectData, Project, AnnualReport, LookUp, User};
use Illuminate\Support\Facades\DB;

function getEmployeeTotalPercentage($year, $employeeId)
{
  // Retrieve both the total approved budget and total actual expenses in a single query
  $result = SubProjectData::selectRaw('SUM(CASE WHEN revised_annual != 0 AND revised_annual IS NOT NULL THEN revised_annual ELSE total_approval_budget END) as total_budget, SUM(actual_expenses_to_date) as total_expenses')
    ->where('employee_id', $employeeId)
    ->where('project_hierarchy_id', 1)
    ->where('year', $year)
    ->first();
  $annualReportDetail = getReportData($year, $employeeId);
  $result['annual_report'] = 0;
  if ($annualReportDetail) {
    $result['annual_report'] = (float)$annualReportDetail->total_annual_budget;
  }
  $result['indirect_total_budget'] = (float)calculateSumIE('total_approved_cost', $employeeId, $year);

  return calculateTotalPercentage($result);
}


function getEmployeeProjectPercentage($projectId, $employeeId, $year, $isProject, $isRemaining)
{

  // Retrieve the total approved budget and total actual expenses for the specified project and employee
  if ($isProject) {
    $result = SubProjectData::selectRaw('SUM(CASE WHEN revised_annual != 0 AND revised_annual IS NOT NULL THEN revised_annual ELSE total_approval_budget END) as total_budget, SUM(actual_expenses_to_date) as total_expenses')
      ->where('project_id', $projectId)
      ->where('employee_id', $employeeId)
      ->where('project_hierarchy_id', 1)
      ->where('year', $year)
      ->first();
  } else {
    $result = SubProjectData::selectRaw('SUM(CASE WHEN revised_annual != 0 AND revised_annual IS NOT NULL THEN revised_annual ELSE total_approval_budget END) as total_budget, SUM(actual_expenses_to_date) as total_expenses')
      ->where('sub_project_id', $projectId)
      ->where('employee_id', $employeeId)
      ->where('project_hierarchy_id', 1)
      ->where('year', $year)
      ->first();
  }
  if ($isRemaining) {
    return  '€' . dutchCurrency($result->total_budget - $result->total_expenses);
    //return calculateRemainingPercentage($result);
  } else {
    return '€' . dutchCurrency($result->total_budget);
    //return calculateUsedPercentage($result);
  }
}

function calculateTotalPercentage($result)
{

  // Extract total approved budget and total actual expenses from the result
  $approvedBudget = $result->total_budget + $result->indirect_total_budget;

  // Calculate the percentage based on the ratio of expenses to approved budget
  if ($result->annual_report > 0) {
    $percentage = ($approvedBudget / $result->annual_report) * 100;
  } else {
    $percentage = 0; // To avoid division by zero
  }

  // Ensure $percentage is a float with 2 decimal places
  $percentage = round((float) $percentage, 2);
  return $percentage;
}

function calculateUsedPercentage($result)
{
  // Extract total approved budget and total actual expenses from the result
  $approvedBudget = $result->total_budget;
  $expenses = $result->total_expenses;

  // Calculate the percentage based on the ratio of expenses to approved budget
  if ($approvedBudget > 0) {
    $percentage = ($expenses / $approvedBudget) * 100;
  } else {
    $percentage = 0; // To avoid division by zero
  }

  // Ensure $percentage is a float with 2 decimal places
  $percentage = round((float) $percentage, 2);
  return $percentage;
}

function calculateRemainingPercentage($result)
{
  // Extract total approved budget and total actual expenses from the result
  return $approvedBudget = $result->total_budget;
  $expenses = $result->total_expenses;

  // Calculate the remaining budget as the difference between approved budget and expenses
  $remainingBudget = $approvedBudget - $expenses;

  // Calculate the percentage based on the ratio of remaining budget to approved budget
  if ($approvedBudget > 0) {
    $percentage = ($remainingBudget / $approvedBudget) * 100;
  } else {
    $percentage = 0; // To avoid division by zero
  }

  // Ensure $percentage is a float with 2 decimal places
  $percentage = round((float) $percentage, 2);
  return $percentage;
}

function getProjectedBudget($year, $employeeId)
{
  // Retrieve both the total approved budget and total actual expenses in a single query
  $result = SubProjectData::where('employee_id', $employeeId)->where('project_hierarchy_id', '!=', 6)
    ->where('year', $year)
    ->sum(DB::raw('CASE WHEN revised_annual = 0 THEN total_approval_budget ELSE revised_annual END'));
  $result = $result + calculateSumIE('total_approved_cost', $employeeId, $year);
  return $result;
}

function getProjectedBudgetOtherDirect($year, $id)
{
  // Retrieve both the total approved budget and total actual expenses in a single query
  $result = SubProjectData::where('project_hierarchy_id', $id)
    ->where('year', $year)
    ->sum(DB::raw('CASE WHEN revised_annual = 0 THEN total_approval_budget ELSE revised_annual END'));
  if ($id == 6) {
    $result = $result + calculateSumODByAllCat('total_approved_cost', $year);
  }
  return $result;
}

function getReportData($year, $employeeId)
{
  return AnnualReport::where('year', $year)->where('employee_id', $employeeId)->first();
}

function getReportDataOtherDirect($year, $employeeId)
{
  return AnnualReport::where('year', $year)->where('is_other_direct', 1)->where('other_direct_expense', $employeeId)->first();
}

function getReportDataIndirect($year, $catId)
{
  return AnnualReport::where('year', $year)->where('is_indirect', 1)->where('indirect_expense', $catId)->first();
}

function getReportsTotal($type, $year, $isOtherDirect)
{
  $query = AnnualReport::where('year', $year);
  if ($isOtherDirect) {
    $query->where('is_other_direct', 1);
  } else {
    $query->where('is_other_direct', 0);
  }
  return $query->sum($type);
}

function getYearList()
{
  $uniqueYears = Project::select(['current_budget_timeline_from', 'current_budget_timeline_to'])
    ->get()
    ->map(function ($project) {
      $startYear = Carbon\Carbon::parse($project->current_budget_timeline_from)->year;
      $endYear = Carbon\Carbon::parse($project->current_budget_timeline_to)->year;
      return range($startYear, $endYear);
    })
    ->flatten()
    ->unique()
    ->sort()
    ->values()
    ->whenEmpty(function ($collection) {
      // If there are no records, add the current year
      $currentYear = Carbon\Carbon::now()->year;
      return collect([$currentYear]);
    })
    ->toArray();
  return $uniqueYears;
}

function getLookUpDetail($id)
{
  $data = LookUp::where('id', $id)->first();
  return $data;
}


function getEmployeeUsedAmount($projectId, $id, $year, $isProject)
{
  // Retrieve the total approved budget and total actual expenses for the specified project and employee
  if ($isProject) {
    $result = SubProjectData::selectRaw('SUM(CASE WHEN revised_annual != 0 AND revised_annual IS NOT NULL THEN revised_annual ELSE total_approval_budget END) as total_budget, SUM(actual_expenses_to_date) as total_expenses')
      ->where('project_id', $projectId)
      ->where('project_hierarchy_id', $id)
      ->where('year', $year)
      ->first();
  } else {
    $result = SubProjectData::selectRaw('SUM(CASE WHEN revised_annual != 0 AND revised_annual IS NOT NULL THEN revised_annual ELSE total_approval_budget END) as total_budget, SUM(actual_expenses_to_date) as total_expenses')
      ->where('sub_project_id', $projectId)
      ->where('project_hierarchy_id', $id)
      ->where('year', $year)
      ->first();
  }
  return $result->total_budget;
}
