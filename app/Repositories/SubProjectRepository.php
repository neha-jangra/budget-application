<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

use App\Models\SubProject;

/**
 * Class SubProjectRepository
 * @package App\Repositories
 * @version July 17, 2023, 2:31 am UTC
 */

class SubProjectRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'sub_project_name','project_id'
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
        return SubProject::class;
    }

}