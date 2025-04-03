<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectYearLinkingWithExact extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'year',
        'exact_project_id',
        'exact_project_code',
        'is_syncable',
        'has_subproject'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
