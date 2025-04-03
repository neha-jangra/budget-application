<?php

namespace App\Repositories;

use App\Constants\RoleConstant;

use App\Constants\ResponseCodes;

/**
 * Class CommonRepository
 * @package App\Repositories
 * @version July 13, 2023, 2:31 am UTC
 */

class CommonRepository
{

    /** @var  UserRepository */
    private $userRepository;

    /** @var  ProjectDetailRepository */
    private $projectDetailRepository;

    /** @var  SubProjectDataRepository */
    private $subProjectDataRepository;

    /** @var  ProjectRepository */
    private $projectRepository;


    public function __construct(UserRepository $userRepository, ProjectDetailRepository $projectDetailRepository, SubProjectDataRepository $subProjectDataRepository, ProjectRepository $projectRepository)
    {
        $this->userRepository            = $userRepository;

        $this->projectDetailRepository   = $projectDetailRepository;

        $this->subProjectDataRepository  = $subProjectDataRepository;

        $this->projectRepository         = $projectRepository;
    }

    public function getUser($data)
    {
        $project_id =  $data['project_id'] ? $data['project_id'] : '';

        $type_id    =  $data['user_type_id']    ? $data['user_type_id']    : '';

        $role_id    =  NULL;

        if ($type_id == 1) {
            $role_id = RoleConstant::EMPLOYEE;
        } else if ($type_id == 2) {
            $role_id = RoleConstant::CONSULTANT;
        } else if ($type_id == 3) {
            $role_id = RoleConstant::SUBGRANTEE;
        }

        if (!empty($data['sub_project_id'])) {
            $_sub_project_employee_id = $this->subProjectDataRepository->where(['project_id' => $project_id, 'sub_project_id' => $data['sub_project_id'], 'year' => $data['year']])->where('project_hierarchy_id', '!=', 6)->pluck('employee_id')->toArray();
        } else {
            $_sub_project_employee_id = $this->subProjectDataRepository->where(['project_id' => $project_id, 'year' => $data['year']])->where('project_hierarchy_id', '!=', 6)->pluck('employee_id')->toArray();
        }
        $_sub_project_employee_id = array_filter($_sub_project_employee_id, function ($value) {
            return !is_null($value);
        });

        $_user = $this->userRepository->whereHas(
            'roles',
            function ($roles) use ($role_id) {
                $roles->where(['role_id' => $role_id])->whereNotIn('role_id', [RoleConstant::DONOR, RoleConstant::ADMIN]);
            }
        )->whereNotIn('id', $_sub_project_employee_id)->orderBy('name')->get();
        return $_user;
    }

    public function updateEstimateBudget($data)
    {
        try {
            $_sub_project_id          = isset($data['sub_project_id']) ? $data['sub_project_id'] : NULL;

            $project_data             = $this->projectDetailRepository->wherefirst(['project_id' => $data['project_id'], 'year' => $data['year']]);

            $project_sub_project_data = $this->projectDetailRepository->wherefirst(['project_id' => $data['project_id'], 'sub_project_id' => $_sub_project_id, 'year' => $data['year']]);

            if (!$project_data) {
                $this->projectDetailRepository->create([

                    'approved_budget'    => $data['approval_budget'],

                    'sub_project_id'     => $_sub_project_id,

                    'remaining_budget'   => $data['remaining_balance'],

                    'expenses'           => $data['actual_expenses'],

                    'project_id'         => $data['project_id'],

                    'year'              => $data['year']
                ]);
            } else {
                if ($project_data->sub_project_id == NULL) {
                    $this->projectDetailRepository->whereUpdate(['project_id' => $data['project_id'], 'year' => $data['year']], [

                        'approved_budget'    => $data['approval_budget'],

                        'remaining_budget'   => $data['remaining_balance'],

                        'expenses'           => $data['actual_expenses'],

                        'sub_project_id'     => $_sub_project_id,

                        'year'              => $data['year']

                    ]);
                } else {

                    if (!$project_sub_project_data) {
                        $this->projectDetailRepository->create([

                            'approved_budget'    => $data['approval_budget'],

                            'sub_project_id'     => $_sub_project_id,

                            'remaining_budget'   => $data['remaining_balance'],

                            'expenses'           => $data['actual_expenses'],

                            'project_id'         => $data['project_id'],

                            'year'              => $data['year']
                        ]);
                    } else {
                        $this->projectDetailRepository->whereUpdate(['project_id' => $data['project_id'], 'sub_project_id' => $_sub_project_id, 'year' => $data['year']], [

                            'approved_budget'    => $data['approval_budget'],

                            'remaining_budget'   => $data['remaining_balance'],

                            'expenses'           => $data['actual_expenses'],

                            'sub_project_id'     => $_sub_project_id,

                            'year'              => $data['year']

                        ]);
                    }
                }
            }

            return array('status' => true, 'statusCode' => ResponseCodes::SUCCESS, 'data' => '', 'message' => 'Estimate budget update successfully!');
        } catch (\Exception $e) {
            return array('status' => false, 'statusCode' => ResponseCodes::INTERNAL_SERVER_ERROR, 'data' => '', 'message' => $e->getMessage());
        }
    }

    public function updateLasttab($data)
    {
        if (isset($data['project_id'])) {
            $this->projectRepository->whereUpdate(['id' => $data['project_id']], [

                'last_tab'          => $data['last_tab'],

                'collapse_last_tab' => NULL

            ]);
        } else {
            return false;
        }
    }
}
