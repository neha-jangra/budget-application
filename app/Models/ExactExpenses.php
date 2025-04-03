<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExactExpenses extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'budgeted_cost',
        'budgeted_revenue',
        'completed',
        'description',
        'part_of',
        'part_of_description',
        'project_id',
        'project_description',
        'item',
        'quantity',
    ];
}
