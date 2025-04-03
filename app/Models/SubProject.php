<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProject extends Model
{
    use HasFactory;

    protected $fillable = ['sub_project_name', 'project_id', 'year', 'exact_id'];

    public function subProjectData()
    {
        return $this->hasMany(SubProjectData::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
