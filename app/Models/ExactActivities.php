<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExactActivities extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'budgeted_cost',
        'budgeted_hours',
        'budgeted_revenue',
        'completed',
        'description',
        'part_of',
        'part_of_description',
        'project_id',
        'project_description'
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];
}
