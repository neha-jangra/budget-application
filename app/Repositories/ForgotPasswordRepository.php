<?php

namespace App\Repositories;


use App\Models\ForgotPasswordVerfication;

use App\Repositories\BaseRepository;

/**
 * Class ForgotPasswordVerfication
 * @package App\Repositories
 * @version July 26, 2021, 2:31 am UTC
 */

class ForgotPasswordRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'code',
        'is_used',
        'link_sent_date_time',
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
        return ForgotPasswordVerfication::class;
    }

}