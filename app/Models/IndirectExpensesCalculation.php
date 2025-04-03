<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class IndirectExpensesCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'indirect_expense_category_id',
        'employee_id',
        'notes',
        'units',
        'cost_per_unit',
        'total_approved_cost',
        'actual_cost_till_date',
        'remaining_cost',
        'year'
    ];

    public function calculateSumByCat($type, $catId, $year = null)
    {
        if ($year) {
            $yearVal = $year;
        } else {
            $yearVal = Carbon::now()->year;
        }
        return $this->where('indirect_expense_category_id', $catId)->where('year', $yearVal)->sum($type);
    }

    public function calculateSumByAllCat($type, $year = null)
    {
        $yearVal = $year ? $year : Carbon::now()->year;
        return   $this->where('year', $yearVal)->sum($type);
    }

    public function calculateSumByEmployee($type, $employeeId, $year = null)
    {
        $yearVal = $year ? $year : Carbon::now()->year;
        return $this->where('employee_id', $employeeId)->where('year', $yearVal)->sum($type);
    }
}
