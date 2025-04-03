<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExactDeliverables extends Model
{
    use HasFactory;

    protected $fillable = [
        'deliverable_id',
        'description',
        'part_of',
        'part_of_description',
        'project_id',
        'project_description',
        'completed',
    ];
}
