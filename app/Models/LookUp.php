<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookUp extends Model
{
    use HasFactory;

    public function projecthierarchdata()
    {
        return $this->hasMany(SubProjectData::class, 'project_hierarchy_id');
    }
}
