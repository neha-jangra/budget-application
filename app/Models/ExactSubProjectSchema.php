<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExactSubProjectSchema extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'sub_project_id',
        'description',
        'exact_id',
        'look_up_id'
    ];
}
