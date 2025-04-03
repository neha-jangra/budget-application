<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

use App\Models\SubProject;
use App\Models\SubProjectData;

/**
 * Class SubProjectDataRepository
 * @package App\Repositories
 * @version July 18, 2023, 2:31 am UTC
 */

class SubProjectDataRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'sub_project_id','project_id','employee_id','note','unit_costs','units','total_approval_budget','actual_expenses_to_date','remaining_balance','project_hierarchy_id'
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
        return SubProjectData::class;
    }

}