<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SubProjectData extends Model
{
    use HasFactory;

    protected $fillable = ['sub_project_id', 'project_id', 'employee_id', 'note', 'unit_costs', 'units', 'total_approval_budget', 'actual_expenses_to_date', 'remaining_balance', 'project_hierarchy_id', 'percentage', 'year', 'revised_annual', 
    'revised_new_budget', 'revised_units', 'revised_unit_amount', 'exact_wbs_description', 'exact_wbs_id',
        'exact_indirect_cost_id'];


    public function calCulateprojectBudget($_segment_id, $project_id, $tabYear, $type, $sub_project_id)
    {
        // If $tabYear is null, use the current year and past year
        if (is_null($tabYear)) {
            $project = Project::where('id', $project_id)->first();
            $startDate = $project->current_budget_timeline_from;
            $start = Carbon::parse($startDate);

            // If $tabYear is null, use the current year and past year
            if (is_null($tabYear)) {
                $currentYear = date('Y');
                // $pastYear = $currentYear - $start->year;
                $conditionYear = [$start->year, (int)$currentYear]; // Between past and current year
            
            } else {
                $conditionYear = [$tabYear, $tabYear]; // Use the provided year
            }
        } else {
            $conditionYear = [$tabYear, $tabYear]; // Use the provided year
        }
        if ($type == 'approval_budget') {
            if ($_segment_id == NULL && $sub_project_id != NULL) {
                return $this->where(['project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('total_approval_budget');
            } elseif ($sub_project_id != NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('total_approval_budget');
            } elseif ($sub_project_id == NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('total_approval_budget');
            } else {
                return $this->where(['project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('total_approval_budget');
            }
        } elseif ($type == 'actual_expenses') {
            if ($_segment_id == NULL && $sub_project_id != NULL) {
                return $this->where(['project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('actual_expenses_to_date');
            } elseif ($sub_project_id != NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('actual_expenses_to_date');
            } elseif ($sub_project_id == NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('actual_expenses_to_date');
            } else {
                return $this->where(['project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('actual_expenses_to_date');
            }
        } elseif ($type == 'remaining_balance') {
            if ($_segment_id == NULL && $sub_project_id != NULL) {
                return $this->where(['project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('remaining_balance');
            } elseif ($sub_project_id != NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('remaining_balance');
            } elseif ($sub_project_id == NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('remaining_balance');
            } else {
                return $this->where(['project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('remaining_balance');
            }
        } elseif ($type == 'revised_new_budget') {
            if ($_segment_id == NULL && $sub_project_id != NULL) {
                return $this->where(['project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('revised_new_budget');
            } elseif ($sub_project_id != NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('revised_new_budget');
            } elseif ($sub_project_id == NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('revised_new_budget');
            } else {
                return $this->where(['project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('revised_new_budget');
            }
        } elseif ($type == 'revised_annual') {
            if ($_segment_id == NULL && $sub_project_id != NULL) {
                return $this->where(['project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('revised_annual');
            } elseif ($sub_project_id != NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('revised_annual');
            } elseif ($sub_project_id == NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('revised_annual');
            } else {
                return $this->where(['project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('revised_annual');
            }
        } elseif ($type == 'revised_unit_amount') {
            if ($_segment_id == NULL && $sub_project_id != NULL) {
                return $this->where(['project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('revised_unit_amount');
            } elseif ($sub_project_id != NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('revised_unit_amount');
            } elseif ($sub_project_id == NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('revised_unit_amount');
            } else {
                return $this->where(['project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('revised_unit_amount');
            }
        } elseif ($type == 'units') {
            if ($_segment_id == NULL && $sub_project_id != NULL) {
                return $this->where(['project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('units');
            } elseif ($sub_project_id != NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('units');
            } elseif ($sub_project_id == NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('units');
            } else {
                return $this->where(['project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('units');
            }
        } elseif ($type == 'unit_costs') {
            if ($_segment_id == NULL && $sub_project_id != NULL) {
                return $this->where(['project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('unit_costs');
            } elseif ($sub_project_id != NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id, 'sub_project_id' => $sub_project_id])->whereBetween('year', $conditionYear)->sum('unit_costs');
            } elseif ($sub_project_id == NULL && $_segment_id != NULL) {
                return $this->where(['project_hierarchy_id' => $_segment_id, 'project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('unit_costs');
            } else {
                return $this->where(['project_id' => $project_id])->whereBetween('year', $conditionYear)->sum('unit_costs');
            }
        }
    }
    

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function projectLookUps()
    {
        return $this->belongsTo(LookUp::class, 'project_hierarchy_id');
    }

    public function subProject()
    {
        return $this->belongsTo(SubProject::class, 'sub_project_id');
    }

    public function indirectcost($sub_project_id, $project_id, $tabYear = null)
    {
        $_data =  $this->where(['project_id' => $project_id, 'sub_project_id' => $sub_project_id, 'year' => $tabYear])->first();

        if ($_data) {
            return true;
        } else {
            return false;
        }
    }
}
