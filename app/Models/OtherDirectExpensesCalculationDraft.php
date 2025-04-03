<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDirectExpensesCalculationDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'other_direct_expense_id',
        'notes',
        'units',
        'cost_per_unit',
        'total_approved_cost',
        'actual_cost_till_date',
        'remaining_cost',
    ];
}
