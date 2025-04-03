<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProjectSyncedStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'look_up_id',
        'sub_project_id',
        'is_synced',
        'project_id'
    ];

    public function lookUp()
    {
        return $this->belongsTo(LookUp::class, 'look_up_id');
    }

    public function subProject()
    {
        return $this->belongsTo(SubProject::class, 'sub_project_id');
    }
}
