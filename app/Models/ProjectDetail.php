<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProjectDetail extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'approved_budget',
        'expenses',
        'remaining_budget',
        'revised_new_budget',
        'revised_annual_budget',
        'project_id',
        'sub_project_id',
        'percentage',
        'year'
    ];

    public function calCulateprojectMatrix($project_id, $tabYear, $type, $sub_project_id)
    {
        $_is_project = $this->where(['project_id' => $project_id])->first();
        $condition = ['project_id' => $project_id];
        $conditionYear = [];
        if ($_is_project && $sub_project_id !== NULL) {
            $condition['sub_project_id'] = $sub_project_id;
        }
        if ($tabYear !== null) {
            $condition['year'] = (int)$tabYear;
        } else {
            $project = Project::where('id', $project_id)->first();
            $startDate = $project->current_budget_timeline_from;
            $start = Carbon::parse($startDate);

            // If $tabYear is null, use the current year and past year
            if (is_null($tabYear)) {
                $currentYear = date('Y');
                //$pastYear = $currentYear - (int)$start->year;
                $conditionYear = [$start->year, (int)$currentYear]; // Between past and current year
            } else {
                $conditionYear['year'] = [$tabYear, $tabYear]; // Use the provided year
            }
        }

        if ($type == 'approval_budget') {
            return $this->where($condition)->sum('approved_budget');
        } elseif ($type == 'actual_expenses') {
            if ($tabYear === null) {
                // Using whereBetween for the between condition
                return $this->where($condition)->whereBetween('year', $conditionYear['year'])->sum('expenses');
            }
            return $this->where($condition)->sum('expenses');
        } elseif ($type == 'remaining_balance') {
            return $this->where($condition)->sum('remaining_budget');
        }
    }


    public function getPercentage($project_id)
    {
        return $this->where(['project_id' => $project_id])->first();
    }
}
