<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InDirectExpenseCategories extends Model
{
    use HasFactory;

    protected $table = 'indirect_cost_categories';
    protected $fillable = ['name'];
}
