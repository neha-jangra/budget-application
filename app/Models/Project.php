<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['exact_id', 'project_code', 'project_name', 'project_type', 'budget', 'project_donor_id', 'donor_email', 'donor_contact_name', 'donor_phone_number', 'ecnl_contact', 'project_duration_from', 'project_duration_to', 'current_budget_timeline_from', 'current_budget_timeline_to', 'date_prepared', 'date_revised', 'confirm_w_finance', 'currency', 'status', 'last_tab', 'phone_number_country_code', 'collapse_last_tab', 'donor_contract_number', 'indirect_rate'];

    public function donor()
    {
        return $this->belongsTo(User::class, 'project_donor_id');
    }

    public function project()
    {
        return $this->hasMany(SubProject::class);
    }

    public function subProjectData()
    {
        return $this->hasMany(SubProjectData::class);
    }

    public function projectDetail()
    {
        return $this->hasMany(ProjectDetail::class);
    }

    public function exactYearsProjects()
    {
        return $this->hasMany(ProjectYearLinkingWithExact::class, 'project_id', 'id');
    }
}
