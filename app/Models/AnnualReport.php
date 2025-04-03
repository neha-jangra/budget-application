<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualReport extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'employee_id',
        'monthly_amount',
        'months',
        'total_annual_budget',
        'projected_budget',
        'balance',
        'year',
        'other_direct_expense',
        'is_other_direct',
        'is_indirect',
        'indirect_expense'
    ];
}
