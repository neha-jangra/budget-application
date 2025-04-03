<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

use App\Models\Project;

/**
 * Class ProjectRepository
 * @package App\Repositories
 * @version July 27, 2021, 2:31 am UTC
 */

class ProjectRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'project_code','project_name','project_type','budget','project_donor_id','donor_email','donor_phone_number','ecnl_contact','project_duration_from','project_duration_to','current_budget_timeline_from','current_budget_timeline_to','date_prepared','date_revised','last_tab','donor_contract_number'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Project::class;
    }

}