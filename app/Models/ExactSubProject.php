<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExactSubProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'exact_id',
        'exact_project_id',
        'description',
    ];
}
