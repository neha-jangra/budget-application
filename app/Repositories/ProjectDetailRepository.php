<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

use App\Models\ProjectDetail;

/**
 * Class ProjectDetailRepository
 * @package App\Repositories
 * @version July 19, 2021, 2:31 am UTC
 */

class ProjectDetailRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'expenses','remaining_budget','previous_budget','balance_of_current_year','project_id','sub_project_id'
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
        return ProjectDetail::class;
    }

}