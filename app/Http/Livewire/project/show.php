<?php

namespace App\Http\Livewire\Project;

use Livewire\Component;
use App\Repositories\ProjectRepository;
use App\Repositories\LookupRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SubProjectRepository;
use App\Repositories\SubProjectDataRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProjectDetailRepository;
use Illuminate\Support\Facades\Route;
use App\Models\Project as Projects;
use App\Constants\RoleConstant;
use App\Models\{User, OtherDirectExpense, SubProjectData, Comment, CommentsAttachment, CommentsTaggedUser, SubProject, ExactSubProject};
use Illuminate\Validation\Rule;
use App\Http\Trait\ToastTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class Show extends Component
{
    use ToastTrait;

    protected $projectRepository, $lookupRepository, $roleRepository, $subProjectRepository, $userRepository, $subProjectDataRepository, $projectDetailRepository;

    public $loader = false, $projectdetail = null, $project_id, $project_segment = null, $donors = [], $users = [], $search_by_name, $project_past_segment = [], $project_future_segment = [], $yearInformation = [], $subProjects, $year;

    public $activeTab;

    public $user_type, $first_name, $last_name, $email, $phone_number, $rows = [], $unit, $employee, $note, $selectedProjectData, $employeeData, $delete_id, $_sub_project, $_sub_project_data, $delete_row_id, $sub_project_model_id, $sub_project_model_name, $sub_project_delete_id, $_show_percentage, $all_past_projects = [], $all_current_projects = [], $all_future_projects = [], $otherDirectExpenses, $commentUsers, $comments, $exactProjectId;

    protected $listeners = ['saveProjectdata', 'unit', 'currentEmployee', 'updateProjectdata', 'deleteProjecthierarchal', 'updateEstimateBudget', 'showPercentage', 'saveRevisionData', 'sendComment', 'setSubProjectData', 'updateSelectedYear'];
    public $comment;
    public $attachments = [];
    public $taggedUsers = [];


    public function hydrate()
    {
        $this->setRepository();
    }

    public function init()
    {
        $this->loader = true;
    }

    public function mount(Request $request)
    {
        $this->project_id = Route::current()->parameter('project');
        $this->activeTab = $request->query('active_tab') ?? 'nav-year-' . date('Y');
    }

    public function clearInput()
    {
        $this->search_by_name = '';
    }

    public function setRepository()
    {
        $this->projectRepository         = app(ProjectRepository::class);

        $this->lookupRepository          = app(LookupRepository::class);

        $this->roleRepository            = app(RoleRepository::class);

        $this->subProjectRepository      = app(subProjectRepository::class);

        $this->userRepository            = app(UserRepository::class);

        $this->subProjectDataRepository  = app(SubProjectDataRepository::class);

        $this->projectDetailRepository   = app(ProjectDetailRepository::class);
    }

    public function render()
    {

        $this->setRepository();
        $this->projectdetail = Projects::with(['project' => function ($q) {
            $q->where('sub_project_name', '!=', NULL);
        }])->where(['id' => $this->project_id])->first();

        $yearInformation = calculateYears($this->projectdetail->current_budget_timeline_from, $this->projectdetail->current_budget_timeline_to);
        
        $this->donors = User::whereHas(
            'roles',
            function ($roles) {
                $roles->whereNotIn('role_id', [RoleConstant::DONOR, RoleConstant::ADMIN]);
            }
        )->orderBy('name')->get();
        $this->yearInformation = $yearInformation;
        $lastArray = end($this->yearInformation);
        $this->exactProjectId = $this->projectdetail->exact_id;
        $this->activeTab = 'nav-year-' . $lastArray['year'] ?? 'nav-year-' . date('Y');
        $this->_sub_project      = $this->subProjectRepository->withWhere('subProjectData', ['project_id' => $this->project_id]);
        $this->_sub_project_data = $this->subProjectDataRepository->wherefirst(['project_id' => $this->project_id, 'sub_project_id' =>  NULL]);
        $this->commentUsers = $this->userRepository->with(['roles'])->whereHas(
            'roles',
            function ($roles) {
                $roles->whereNotIn('role_id', [RoleConstant::DONOR, RoleConstant::EMPLOYEE, RoleConstant::CONSULTANT, RoleConstant::SUBGRANTEE]);
            }
        )->orderBy('name', 'asc')->get();
        $this->comments = Comment::with(['replies', 'attachments'])->where('project_id', $this->project_id)->get();
        // saveExactLineItemsInBudgetApp($this->project_id, $lastArray['year']??date('Y'));
        return view('livewire.project.show');
    }



    public function saveProjectdata($data)
    {
      
        $this->selectedProjectData = $data;
        $_last_tab         = isset($data['last_tab'])       ? $data['last_tab']       : NULL;
        $year         = isset($data['year'])       ? $data['year']       : NULL;
        $_subb_project_id  = isset($data['sub_project_id']) ? $data['sub_project_id'] : NULL;
        $_percentage       = 100;

        $_look_up = $this->lookupRepository->wherefirst(['look_up_value' => preg_replace('/\s+/', ' ', trim($data['title']))]);

        $this->projectRepository->whereUpdate(['id' => $this->project_id], [
            'last_tab'           => $_last_tab,
            'collapse_last_tab'  => commaSeprated($_look_up->id, $_subb_project_id)
        ]);

        $subProjectData= $this->subProjectDataRepository->create([

            'sub_project_id'            => isset($data['sub_project_id']) ? $data['sub_project_id'] : NULL,

            'employee_id'               => $data['employee'] ?? $data['employee_id'],

            'note'                      => $data['note'],

            'year'                      => $year,

            'units'                     => removeDutchFormat($data['unit']),

            'unit_costs'                => removeDutchFormat($data['unit_costs']),

            'actual_expenses_to_date'   => removeDutchFormat($data['expenses']),

            'remaining_balance'         => removeDutchFormat($data['remaining_balanace']),

            'project_hierarchy_id'      => $_look_up->id,

            'project_id'                => $this->project_id,

            'total_approval_budget'     => removeDutchFormat($data['total_approval_budget']),

            'percentage'                => (removeDutchFormatPercentage($data['indirect_cost_percentage'])) ? removeDutchFormatPercentage($data['indirect_cost_percentage']) : 0

        ]);

        $_sub_project_id          = isset($data['sub_project_id']) ? $data['sub_project_id'] : NULL;

        $project_data             = $this->projectDetailRepository->wherefirst(['project_id' => $this->project_id, 'year' => $year]);

        $project_sub_project_data = $this->projectDetailRepository->wherefirst(['project_id' => $this->project_id, 'sub_project_id' => $_sub_project_id, 'year' => $year]);

        $remove_indirect_cost_percentage = removeDutchFormatPercentage($data['indirect_cost_percentage']);

        if (!$project_data) {
            $this->projectDetailRepository->create([

                'approved_budget'    => removeDutchFormat($data['total_approval_budget']) + (removeDutchFormat($data['total_approval_budget']) * $remove_indirect_cost_percentage) / $_percentage,

                'sub_project_id'     => $_sub_project_id,

                'remaining_budget'   => removeDutchFormat($data['remaining_balanace']) + (removeDutchFormat($data['remaining_balanace']) * $remove_indirect_cost_percentage) / $_percentage,

                'expenses'           => removeDutchFormat($data['expenses']) + (removeDutchFormat($data['expenses']) * $remove_indirect_cost_percentage) / $_percentage,

                'project_id'         => $this->project_id,
                'year'                      => $year,
            ]);
        } else {
            if ($project_data->sub_project_id == NULL) {
                $this->projectDetailRepository->whereUpdate(['project_id' => $this->project_id,  'year' => $year], [

                    'approved_budget'    => $project_sub_project_data->approved_budget + (removeDutchFormat($data['total_approval_budget']) + (removeDutchFormat($data['total_approval_budget']) * $remove_indirect_cost_percentage) / $_percentage),

                    'remaining_budget'   => $project_sub_project_data->remaining_budget + (removeDutchFormat($data['remaining_balanace']) + (removeDutchFormat($data['remaining_balanace']) * $remove_indirect_cost_percentage) / $_percentage),

                    'expenses'           => $project_sub_project_data->expenses + (removeDutchFormat($data['expenses']) + (removeDutchFormat($data['expenses']) * $remove_indirect_cost_percentage) / $_percentage),

                    'sub_project_id'     => $_sub_project_id,

                ]);
            } else {

                if (!$project_sub_project_data) {
                    $this->projectDetailRepository->create([

                        'approved_budget'    => removeDutchFormat($data['total_approval_budget']) + (removeDutchFormat($data['total_approval_budget']) * $remove_indirect_cost_percentage) / $_percentage,

                        'sub_project_id'     => $_sub_project_id,

                        'remaining_budget'   => removeDutchFormat($data['remaining_balanace']) + (removeDutchFormat($data['remaining_balanace']) * $remove_indirect_cost_percentage) / $_percentage,

                        'expenses'           => removeDutchFormat($data['expenses']) + (removeDutchFormat($data['expenses']) * $remove_indirect_cost_percentage) / $_percentage,

                        'project_id'         => $this->project_id,
                        'year'                      => $year,
                    ]);
                } else {
                    $this->projectDetailRepository->whereUpdate(['project_id' => $this->project_id, 'sub_project_id' => $_sub_project_id, 'year' => $year], [

                        'approved_budget'    => $project_sub_project_data->approved_budget + (removeDutchFormat($data['total_approval_budget']) + (removeDutchFormat($data['total_approval_budget']) * $remove_indirect_cost_percentage) / $_percentage),

                        'remaining_budget'   => $project_sub_project_data->remaining_budget + (removeDutchFormat($data['remaining_balanace']) + (removeDutchFormat($data['remaining_balanace']) * $remove_indirect_cost_percentage) / $_percentage),

                        'expenses'           => $project_sub_project_data->expenses + (removeDutchFormat($data['expenses']) + (removeDutchFormat($data['expenses']) * $remove_indirect_cost_percentage) / $_percentage),

                        'sub_project_id'     => $_sub_project_id,

                    ]);
                }
            }
        }

        $this->activeTab = 'nav-year-' . $year;

        $this->render();
        saveActivitiesExpensesInExact($subProjectData);

        $this->emit('reinitializeSelect2');
        $this->emit('refreshSubProjectModal');

        $this->emit('swal:alert', [

            'title'       => 'Success!',

            'text'        => 'Data Added Successfully!',

            'icon'        => 'success',

            'redirectUrl' => '',

            'status'      => 'success'
        ]);

        $this->emit('projectCreateSaved', true);
    }

    public function showPercentage($data)
    {
        $this->_show_percentage = (removeDutchFormatPercentage($data)) ?  removeDutchFormatPercentage($data) : 0;
    }

    public function confirmDelete($id, $_last_tab = NULL, $_subb_project_id = NULL, $_show_percentage = NULL)
    {

        if ($_subb_project_id == 'NULL') {
            $_subb_project_id  = NULL;
        }

        $this->delete_id = $id;

        $this->_show_percentage = (removeDutchFormatPercentage($_show_percentage)) ?  removeDutchFormatPercentage($_show_percentage) : 0;

        $_look_up = $this->lookupRepository->wherefirst(['id' => $_last_tab]);

        $this->projectRepository->whereUpdate(['id' => $this->project_id], [

            'last_tab'           => $_subb_project_id,

            'collapse_last_tab'  => commaSeprated($_look_up->id, $_subb_project_id)

        ]);
    }

    public function deleteProjecthierarchal()
    {
        try {
            $year = date('Y');
            $_minus_approved_budget            = 0;

            $_minus_expenses                   = 0;

            $_minus_remaining_budget           = 0;

            $_percentage                       = 100;

            $_sub_project_employee_data3       = $this->subProjectDataRepository->whereget(['id' => $this->delete_id]);

            $_minus_approved_budget            = 0;

            $_minus_expenses                   = 0;

            $_minus_remaining_budget           = 0;

            foreach ($_sub_project_employee_data3 as $key => $_sub_project_employee_data) {
                $_project_detail_data35          = $this->projectDetailRepository->wherefirst(['project_id' => $_sub_project_employee_data->project_id, 'sub_project_id' => $_sub_project_employee_data->sub_project_id, 'year' => $_sub_project_employee_data->year]);

                $_sub_project_percentage         =  ($_sub_project_employee_data->percentage) ? $_sub_project_employee_data->percentage : 0;

                $_minus_approved_budget          = ($_sub_project_employee_data->total_approval_budget * $_sub_project_percentage) / $_percentage;

                $_minus_expenses                 = ($_sub_project_employee_data->actual_expenses_to_date * $_sub_project_percentage) / $_percentage;

                $_minus_remaining_budget         =  ($_sub_project_employee_data->remaining_balance * $_sub_project_percentage) / $_percentage;


                $_grant_approval = $_project_detail_data35->approved_budget   - ($_minus_approved_budget  + $_sub_project_employee_data->total_approval_budget);

                $_grant_expenses =  $_project_detail_data35->expenses         - ($_minus_expenses         + $_sub_project_employee_data->actual_expenses_to_date);

                $_grant_remaing  =  $_project_detail_data35->remaining_budget - ($_minus_remaining_budget + $_sub_project_employee_data->remaining_balance);

                if ($_sub_project_employee_data->sub_project_id == NULL) {
                    $this->projectDetailRepository->whereUpdate(['project_id' => $_project_detail_data35->project_id, 'year' => $_sub_project_employee_data->year], [

                        'approved_budget'       =>  $_grant_approval,

                        'expenses'              =>  $_grant_expenses,

                        'remaining_budget'      =>  $_grant_remaing,
                    ]);

                    if ($_grant_approval == 0) {
                        $this->projectDetailRepository->Wheredelete(['id' => $_project_detail_data35->project_id]);
                    }
                } else {

                    $this->projectDetailRepository->whereUpdate(['project_id' => $_project_detail_data35->project_id, 'sub_project_id' => $_project_detail_data35->sub_project_id, 'year' => $_sub_project_employee_data->year], [

                        'approved_budget'       =>  $_grant_approval,

                        'expenses'              =>  $_grant_expenses,

                        'remaining_budget'      =>  $_grant_remaing,
                    ]);

                    if ($_grant_approval == 0) {
                        $this->projectDetailRepository->Wheredelete(['project_id' => $_project_detail_data35->project_id, 'sub_project_id' => $_project_detail_data35->sub_project_id, 'year' => $_sub_project_employee_data->year]);
                    }
                }
                $year = $_sub_project_employee_data->year;
            }
            $getSubProjectDataDetail = SubProjectData::where('id', $this->delete_id)->first();
            $isActivity = $getSubProjectDataDetail->project_hierarchy_id == 1;
            deleteExactActivityOrExpense($isActivity, $getSubProjectDataDetail->exact_wbs_id);
            $this->subProjectDataRepository->Wheredelete(['id' => $this->delete_id]);
            $subproject = $this->subProjectDataRepository->wherefirst(['project_id' => $this->project_id, 'sub_project_id' =>  NULL]);
            $this->activeTab = 'nav-year-' . $year;
            $url = '';
            if (!$subproject) {
                $url = '/project/' . $this->project_id . '?active_tab=' . $this->activeTab;
            }
            $this->emit('deleteItemProject');
            $this->render();
            $this->emit('reinitializeSelect2');
            $this->emit('swal:alert', [

                'title' => 'Success!',

                'text' => 'Data Deleted Successfully!',

                'icon' => 'success',

                'redirectUrl' => '',

                'status'  => 'success'
            ]);
        } catch (\Exception $e) {
        }
    }

    public function confirmAllDelete($user_id, $_last_tab_collapse, $_show_percentage = NULL)
    {

        $this->delete_id = $user_id;
        $detail = $this->subProjectDataRepository->where(['id' => $this->delete_id])->first();
        $this->_show_percentage = (removeDutchFormatPercentage($_show_percentage)) ?  removeDutchFormatPercentage($_show_percentage) : 0;

        $this->projectRepository->whereUpdate(['id' => $this->project_id], [

            'last_tab'           => 0,

            'collapse_last_tab'  => $_last_tab_collapse

        ]);

        $this->activeTab = 'nav-year-' . $detail->year;
    }

    public function deleteUserProject()
    {
        try {

            $_sub_project_data_id = $this->subProjectDataRepository->wherefirst(['id' => $this->delete_id]);
            $year = $_sub_project_data_id->year;

            $_minus_approved_budget            = 0;

            $_minus_expenses                   = 0;

            $_minus_remaining_budget           = 0;

            $_sub_project_employee_data2 = $this->subProjectDataRepository->wherefirst(['id' => $this->delete_id]);

            $_percentage                = 100;

            $_sub_project_employee_data3       = $this->subProjectDataRepository->whereget(['employee_id' => $_sub_project_data_id->employee_id, 'project_id' => $_sub_project_data_id->project_id]);

            $_sub_project_employee_data333     = $this->subProjectDataRepository->where(['employee_id' => $_sub_project_data_id->employee_id, 'project_id' => $_sub_project_data_id->project_id])->pluck('id')->toArray();

            $_minus_approved_budget = 0;

            $_minus_expenses  = 0;

            $_minus_remaining_budget = 0;

            foreach ($_sub_project_employee_data3 as $key => $_sub_project_employee_data) {
                $_project_detail_data35          = $this->projectDetailRepository->wherefirst(['project_id' => $_sub_project_employee_data->project_id, 'sub_project_id' => $_sub_project_employee_data->sub_project_id]);

                $_sub_project_percentage         =  ($_sub_project_employee_data->percentage) ? $_sub_project_employee_data->percentage : 0;

                $_minus_approved_budget         = ($_sub_project_employee_data->total_approval_budget * $_sub_project_percentage) / $_percentage;

                $_minus_expenses                = ($_sub_project_employee_data->actual_expenses_to_date * $_sub_project_percentage) / $_percentage;

                $_minus_remaining_budget        =  ($_sub_project_employee_data->remaining_balance * $_sub_project_percentage) / $_percentage;


                $_grant_approval = $_project_detail_data35->approved_budget - ($_minus_approved_budget + $_sub_project_employee_data->total_approval_budget);

                $_grant_expenses =  $_project_detail_data35->expenses - ($_minus_expenses + $_sub_project_employee_data->actual_expenses_to_date);

                $_grant_remaing  =  $_project_detail_data35->remaining_budget - ($_minus_remaining_budget + $_sub_project_employee_data->remaining_balance);


                if ($_sub_project_employee_data->sub_project_id == NULL) {
                    $this->projectDetailRepository->whereUpdate(['project_id' => $_project_detail_data35->project_id], [

                        'approved_budget'       =>  $_grant_approval,

                        'expenses'              =>  $_grant_expenses,

                        'remaining_budget'      =>  $_grant_remaing,
                    ]);

                    if ($_grant_approval == 0) {
                        $this->projectDetailRepository->Wheredelete(['id' => $_project_detail_data35->project_id]);
                    }
                } else {

                    $this->projectDetailRepository->whereUpdate(['project_id' => $_project_detail_data35->project_id, 'sub_project_id' => $_project_detail_data35->sub_project_id], [

                        'approved_budget'       =>  $_grant_approval,

                        'expenses'              =>  $_grant_expenses,

                        'remaining_budget'      =>  $_grant_remaing,
                    ]);

                    if ($_grant_approval == 0) {
                        $this->projectDetailRepository->Wheredelete(['project_id' => $_project_detail_data35->project_id, 'sub_project_id' => $_project_detail_data35->sub_project_id]);
                    }
                }
            }

            $this->activeTab = 'nav-year-' . $year;
            $this->subProjectDataRepository->whereIn('id', $_sub_project_employee_data333)->delete();

            $this->emit('deleteItemProject');

            $this->render();

            $this->emit('reinitializeSelect2');

            $this->emit('swal:alert', [

                'title' => 'Success!',

                'text' => 'Data Deleted Successfully!',

                'icon' => 'success',

                'redirectUrl' => '',

                'status'  => 'success'
            ]);
        } catch (\Exception $th) {
        }
    }

    public function updateProjectdata($data)
    {
        $_percentage              = 100;

        $_sub_project_id          = isset($data['sub_project_id']) ? $data['sub_project_id'] : NULL;
        $year          = isset($data['year']) ? $data['year'] : date('Y');

        $total_approval_budget    = 0;

        $remaining_balanace       = 0;

        $expenses                 = 0;

        $remove_indirect_cost_percentage = removeDutchFormatPercentage($data['indirect_cost_percentage']);


        $this->subProjectDataRepository->whereUpdate(['id' => $data['sub_project_data_id'], 'year' => $year], [

            'sub_project_id'            => $_sub_project_id,

            'employee_id'               => $data['employee'],

            'note'                      => $data['note'],

            'year'                      => $data['year'],

            'units'                     => removeDutchFormat($data['unit']),

            'unit_costs'                => removeDutchFormat($data['unit_costs']),

            'actual_expenses_to_date'   => removeDutchFormat($data['expenses']),

            'remaining_balance'         => $data['remaining_balanace'],

            'total_approval_budget'     => removeDutchFormat($data['total_approval_budget']),

            'percentage'                => removeDutchFormatPercentage($data['indirect_cost_percentage']) ? removeDutchFormatPercentage($data['indirect_cost_percentage']) : 0

        ]);

        $sub_project_data = $this->subProjectDataRepository->where(['sub_project_id' => $_sub_project_id, 'project_id' => $this->project_id, 'year' => $year])->get();

        foreach ($sub_project_data as $key => $sub_project_info) {
            $total_approval_budget += $sub_project_info->total_approval_budget;

            $remaining_balanace    += $sub_project_info->remaining_balance;

            $expenses              += $sub_project_info->actual_expenses_to_date;
        }

        if ($_sub_project_id == NULL) {
            $this->projectDetailRepository->whereUpdate(['project_id' => $this->project_id, 'year' => $year], [

                'approved_budget'    =>  $total_approval_budget + (($total_approval_budget * $remove_indirect_cost_percentage) / $_percentage),

                'remaining_budget'   =>  $remaining_balanace + (($remaining_balanace * $remove_indirect_cost_percentage) / $_percentage),

                'expenses'           =>  $expenses + (($expenses * $remove_indirect_cost_percentage) / $_percentage),

            ]);
        } else {
            $this->projectDetailRepository->whereUpdate(['project_id' => $this->project_id, 'sub_project_id' => $_sub_project_id, 'year' => $year], [

                'approved_budget'    =>  $total_approval_budget + (($total_approval_budget * $remove_indirect_cost_percentage) / $_percentage),

                'remaining_budget'   =>  $remaining_balanace + (($remaining_balanace * $remove_indirect_cost_percentage) / $_percentage),

                'expenses'           =>  $expenses + (($expenses * $remove_indirect_cost_percentage) / $_percentage),

            ]);
        }

        $_sub_Project_Data = $this->subProjectDataRepository->wherefirst(['id' => $data['sub_project_data_id'], 'year' => $year]);
        saveActivitiesExpensesInExact($_sub_Project_Data);

        $_last_tab = isset($data['last_tab']) ? $data['last_tab'] : NULL;
        $this->activeTab = 'nav-year-' . $data['year'];

        $this->projectRepository->whereUpdate(['id' => $this->project_id], [
            'last_tab'           => $_last_tab,
            'collapse_last_tab'  => commaSeprated($_sub_Project_Data->project_hierarchy_id, $_sub_project_id)
        ]);

        $this->emit('reinitializeSelect2');

        $this->emit('swal:alert', [

            'title'       => 'Success!',

            'text'        => 'Data updated successfully!',

            'icon'        => 'success',

            'redirectUrl' => '',

            'status'      => 'success'
        ]);
    }

    public function editSubProjectId($id)
    {
        $_sub_project                      = SubProject::where('id', $id)->first();

        $this->sub_project_model_name      = $_sub_project->sub_project_name;

        $this->sub_project_model_id        = $_sub_project->id;
    }

    public function setSubProjectData($subProjectId, $subProjectName)
    {
        // Set the component's properties with the values passed from JS
        $this->sub_project_model_id = $subProjectId;
        $this->sub_project_model_name = $subProjectName;
    }

    public function editSubProject()
    {
        $validatedData = $this->validate([
            'sub_project_model_name'     => ['required'],
        ]);
        $_sub_project_attribute  =  array(
            'sub_project_name'     =>  $this->sub_project_model_name,
        );
        $this->subProjectRepository->whereUpdate(['id' => $this->sub_project_model_id], $_sub_project_attribute);
        $_sub_project = $this->subProjectRepository->wherefirst(['id' => $this->sub_project_model_id]);
        $this->emit('commonModal', 'edit_sub_project_modal');
        $this->activeTab = 'nav-year-' . $_sub_project->year;
        $this->toastMessage('Success!', 'Sub Project Edited Successfully!', '', 'success');
    }

    public function confirmsubDelete($id)
    {
        $this->sub_project_delete_id = $id;
    }
    

    public function deletesubproject()
    {
        try {

            $sub_project_data = $this->projectDetailRepository->where(['project_id' => $this->project_id])->count();
            $_sub_project = $this->subProjectRepository->wherefirst(['id' => $this->sub_project_delete_id]);

            if ($sub_project_data > 1) {
                $this->projectDetailRepository->Wheredelete(['sub_project_id' => $this->sub_project_delete_id]);
                $this->subProjectDataRepository->Wheredelete(['sub_project_id' => $this->sub_project_delete_id]);
            } else {
                $this->projectDetailRepository->Wheredelete(['sub_project_id' => $this->sub_project_delete_id]);
                $this->subProjectDataRepository->Wheredelete(['sub_project_id' => $this->sub_project_delete_id]);
            }

            $this->subProjectRepository->Wheredelete(['id' => $this->sub_project_delete_id]);

            $this->projectRepository->whereUpdate(['id' => $this->project_id], ['last_tab' => '0']);

            $this->emit('commonModal', 'delete_sub_project');

            $this->toastMessage('Success!', 'Sub Project Deleted Successfully!', '/project' . '/' . $this->project_id. '?active_tab=nav-year-'. $_sub_project->year, 'success');
        } catch (\Exception $e) {

            $this->toastMessage('Error!', $e->getMessage(), '', 'error');
        }
    }

    public function changeTab($sub_project_id, $project_id)
    {
        if ($sub_project_id == '0') {
            $this->projectRepository->whereUpdate(['id' => $project_id], ['last_tab' => '0']);
            return true;
        }
        $_sub_project = $this->subProjectRepository->wherefirst(['id' => $sub_project_id]);

        $this->projectRepository->whereUpdate(['id' => $project_id], ['last_tab' => $_sub_project->id]);
    }

    public function updateEstimateBudget($data)
    {
        
        // try {
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

                    'percentage'         => $data['percentage'],

                    'year'              => $data['year']
                ]);
            } else {
                if ($project_data->sub_project_id == NULL) {
                    $this->projectDetailRepository->whereUpdate(['project_id' => $data['project_id'], 'year' => $data['year']], [

                        'approved_budget'    => $data['approval_budget'],

                        'remaining_budget'   => $data['remaining_balance'],

                        'expenses'           => $data['actual_expenses'],

                        'sub_project_id'     => $_sub_project_id,

                        'percentage'         => $data['percentage'],

                        'year'              => $data['year']

                    ]);

                    $this->subProjectDataRepository->whereUpdate(['project_id' => $data['project_id'], 'year' => $data['year']], ['percentage' => $data['percentage']]);
                } else {

                    if (!$project_sub_project_data) {
                        $this->projectDetailRepository->create([

                            'approved_budget'    => $data['approval_budget'],

                            'sub_project_id'     => $_sub_project_id,

                            'remaining_budget'   => $data['remaining_balance'],

                            'expenses'           => $data['actual_expenses'],

                            'project_id'         => $data['project_id'],

                            'percentage'         => $data['percentage'],

                            'year'              => $data['year']
                        ]);
                    } else {
                        $this->projectDetailRepository->whereUpdate(['project_id' => $data['project_id'], 'sub_project_id' => $_sub_project_id, 'year' => $data['year']], [

                            'approved_budget'    => $data['approval_budget'],

                            'remaining_budget'   => $data['remaining_balance'],

                            'expenses'           => $data['actual_expenses'],

                            'sub_project_id'     => $_sub_project_id,

                            'percentage'         => $data['percentage'],

                            'year'              => $data['year']

                        ]);

                        $this->subProjectDataRepository->whereUpdate(['project_id' => $data['project_id'], 'year' => $data['year'], 'sub_project_id' => $_sub_project_id], ['percentage' => $data['percentage']]);
                    }
                }
            }
            saveIndirectCostInExact($data);
            $this->activeTab = 'nav-year-' . $data['year'];
            $this->toastMessage('Success!', 'Data updated successfully!', '', 'success');
        // } catch (\Exception $e) {
        //     $this->toastMessage('Error!', $e->getMessage(), '', 'error');
        // }
    }

    public function saveRevisionData($data)
    {
        $subProjectId = isset($data['subProjectId']) ? $data['subProjectId'] : NULL;
        $subProjectData = $this->subProjectDataRepository->wherefirst(['id' => $data['recordId']]);
        $this->subProjectDataRepository->whereUpdate(['id' => $data['recordId']], [
            'revised_annual' => $data['revised_annual'],
            'revised_units' => $data['revised_units'],
            'revised_unit_amount' => $data['revised_unit_amount'],
            'revised_new_budget' => $data['revised_new_budget']
        ]);
        $_last_tab = isset($data['last_tab']) ? $data['last_tab'] : NULL;
        $this->projectRepository->whereUpdate(['id' => $this->project_id], [
            'last_tab' => $_last_tab,
            'collapse_last_tab'  => commaSeprated($subProjectData->project_hierarchy_id, $subProjectId)
        ]);

        //adding the revised budget in the project detail table to have the correct matrix
        $sub_project_data = $this->subProjectDataRepository->where(['sub_project_id' => $subProjectId, 'project_id' => $this->project_id, 'year' => $subProjectData->year])->get();
        $total_approval_budget    = 0;
        $remaining_balanace       = 0;
        $expenses                 = 0;
        foreach ($sub_project_data as $key => $sub_project_info) {
            if ($sub_project_info->revised_annual != 0) {
                $total_approval_budget += $sub_project_info->revised_annual;
            } else {
                $total_approval_budget += $sub_project_info->total_approval_budget;
            }
            $remaining_balanace    += $sub_project_info->remaining_balance;
            $expenses              += $sub_project_info->actual_expenses_to_date;
        }
        $_percentage = 100;
        if ($subProjectId == NULL) {
            $this->projectDetailRepository->whereUpdate(['project_id' => $this->project_id, 'year' => $subProjectData->year], [
                'approved_budget'    =>  $total_approval_budget + (($total_approval_budget * $subProjectData->percentage) / $_percentage),
                'remaining_budget'   =>  $remaining_balanace + (($remaining_balanace * $subProjectData->percentage) / $_percentage),
                'expenses'           =>  $expenses + (($expenses * $subProjectData->percentage) / $_percentage),
            ]);
        } else {
            $this->projectDetailRepository->whereUpdate(['project_id' => $this->project_id, 'sub_project_id' => $subProjectId, 'year' => $subProjectData->year], [
                'approved_budget'    =>  $total_approval_budget + (($total_approval_budget * $subProjectData->percentage) / $_percentage),
                'remaining_budget'   =>  $remaining_balanace + (($remaining_balanace * $subProjectData->percentage) / $_percentage),
                'expenses'           =>  $expenses + (($expenses * $subProjectData->percentage) / $_percentage),
            ]);
        }
        $this->activeTab = "nav-revision-for-current-year";
        $this->emit('reinitializeSelect2');
        $this->emit('swal:alert', [
            'title'       => 'Success!',
            'text'        => 'Data updated successfully!',
            'icon'        => 'success',
            'redirectUrl' => '',
            'status'      => 'success'
        ]);
    }

    public function createSubproject()
    {
        $validatedData = $this->validate([
            'sub_project_id' => 'required',
        ], [
            'sub_project_id.required' => 'The Sub Project field is required.',
        ]);


        /** seprate the user table data */

        $_sub_project_attribute  =  array(
            'sub_project_name'     =>  $this->sub_project,
            'project_id'           =>  $this->project_id,
            'year'                 =>  $this->year,
            'exact_id'         => $validatedData['sub_project_id']
        );

        $_sub_project = $this->subProjectRepository->create($_sub_project_attribute);

        $_sub_project_data = $this->subProjectDataRepository->wherefirst(['project_id' => $this->project_id, 'sub_project_id' =>  NULL, 'year' => $this->year]);
        if ($_sub_project_data) {
            if ($_sub_project_data->sub_project_id === NULL) {
                $this->subProjectDataRepository->whereUpdate(['project_id' => $this->project_id, 'sub_project_id' =>  NULL, 'year' => $this->year], ['sub_project_id' => $_sub_project->id]);
            }
        }
        $_is_project_detail = $this->projectDetailRepository->wherefirst(['project_id' => $this->project_id]);
        if ($_is_project_detail) {
            $_is_sub_project = $this->projectDetailRepository->wherefirst(['sub_project_id' => NULL, 'project_id' => $this->project_id]);
            if ($_is_sub_project) {
                $this->projectDetailRepository->whereUpdate(['project_id' => $this->project_id], [
                    'sub_project_id'   => $_sub_project->id,
                ]);
            } else {
                $this->projectDetailRepository->create([
                    'project_id'       => $this->project_id,
                    'approved_budget'  => 0,
                    'expenses'         => 0,
                    'remaining_budget' => 0,
                    'sub_project_id'   => $_sub_project->id,
                    'year' => $this->year
                ]);
            }
        } else {
            $this->projectDetailRepository->create([
                'project_id'       => $this->project_id,
                'approved_budget'  => 0,
                'expenses'         => 0,
                'remaining_budget' => 0,
                'sub_project_id'   => $_sub_project->id,
                'year' => $this->year
            ]);
        }

        $this->projectRepository->whereUpdate(['id' => $this->project_id], [

            'last_tab'    => $_sub_project->id,

        ]);

        $this->emit('closeSubprojectmodal', 'data');
        $this->emit('swal:alert', [

            'title'        => 'Success!',

            'text'         => 'Sub Project Created Successfully!',

            'icon'         => 'success',

            'redirectUrl'  => '/project' . '/' . $this->project_id . '?active_tab=nav-year-' . $this->year,

            'status'       => 'success'
        ]);

        $this->emit('refreshSelect');
    }

    public function updateSelectedYear($year)
    {
        $this->emit('yearUpdated', $year);
    }
}
