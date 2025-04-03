<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndirectExpensesCalculationDraft extends Model
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
    ];
}
