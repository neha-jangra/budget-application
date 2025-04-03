<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExactProjects extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'exact_id',
        'project_code',
        'description',
        'start_date',
        'end_date',
        'part_of',
        'account',
        'account_id',
        'account_name',
        'account_code',
        'account_contact'
    ];
}
