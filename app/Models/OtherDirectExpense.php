<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDirectExpense extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function calculations()
    {
        return $this->hasOne(OtherDirectExpensesCalculation::class, 'other_direct_expense_id');
    }
}
