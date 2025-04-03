<?php

namespace App\Repositories;

use App\Models\LookUp;

use App\Repositories\BaseRepository;

/**
 * Class LookupRepository
 * @package App\Repositories
 * @version July 12, 2023, 2:31 am UTC
 */

class LookupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'look_up_type',
        'look_up_field',
        'look_up_value',
        'sort_order'
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
        return LookUp::class;
    }

}