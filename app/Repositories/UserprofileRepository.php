<?php

namespace App\Repositories;


use App\Models\UserProfile;
use App\Repositories\BaseRepository;

/**
 * Class UserprofileRepository
 * @package App\Repositories
 * @version July 29, 2021, 2:31 am UTC
 */

class UserprofileRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company','gender','address','city','state','pin_code','country','country_code','user_id'
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
        return UserProfile::class;
    }

}