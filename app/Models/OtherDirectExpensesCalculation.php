<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OtherDirectExpensesCalculation extends Model
{
    use HasFactory;

    protected $table = 'other_direct_expenses_calculation';

    protected $fillable = [
        'other_direct_expense_id',
        'notes',
        'units',
        'cost_per_unit',
        'total_approved_cost',
        'actual_cost_till_date',
        'remaining_cost',
        'indirect_expense_category_id',
        'year'
    ];


    public function calculateSum($type, $catId, $year)
    {
        if ($year) {
            $yearVal = $year;
        } else {
            $yearVal = Carbon::now()->year;
        }
        if ($type == 'total_approved_cost') {
            return $this->where('indirect_expense_category_id', $catId)->where('year', $yearVal)->sum('total_approved_cost');
        } elseif ($type == 'actual_cost_till_date') {
            return $this->where('indirect_expense_category_id', $catId)->where('year', $yearVal)->sum('actual_cost_till_date');
        } elseif ($type == 'remaining_cost') {
            return $this->where('indirect_expense_category_id', $catId)->where('year', $yearVal)->sum('remaining_cost');
        }
    }

    public function calculateSumByOtherDirect($type, $id, $year)
    {
        if ($year) {
            $yearVal = $year;
        } else {
            $yearVal = Carbon::now()->year;
        }
        if ($type == 'total_approved_cost') {
            return $this->where('year', date('Y'))->where('other_direct_expense_id', $id)->where('year', $yearVal)->sum('total_approved_cost');
        } elseif ($type == 'units') {
            return $this->where('year', date('Y'))->where('other_direct_expense_id', $id)->where('year', $yearVal)->sum('units');
        } elseif ($type == 'cost_per_unit') {
            return $this->where('year', date('Y'))->where('other_direct_expense_id', $id)->where('year', $yearVal)->whereNotNull('cost_per_unit')
                ->where('cost_per_unit', '>', 0)->avg('cost_per_unit');
        } elseif ($type == 'actual_cost_till_date') {
            return $this->where('year', date('Y'))->where('other_direct_expense_id', $id)->where('year', $yearVal)->sum('actual_cost_till_date');
        } elseif ($type == 'remaining_cost') {
            return $this->where('year', date('Y'))->where('other_direct_expense_id', $id)->where('year', $yearVal)->sum('remaining_cost');
        }
    }

    public function calculateSumODByAllCat($type, $year = null)
    {
        if ($year) {
            $yearVal = $year;
        } else {
            $yearVal = Carbon::now()->year;
        }
        return $this->where('year', $yearVal)->sum($type);
    }
}
