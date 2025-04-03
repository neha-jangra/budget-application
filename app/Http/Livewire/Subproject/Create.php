<?php

namespace App\Http\Livewire\Subproject;

use Livewire\Component;

use App\Repositories\SubProjectRepository;

use App\Repositories\SubProjectDataRepository;

use App\Repositories\ProjectDetailRepository;

use App\Repositories\ProjectRepository;

use Illuminate\Support\Facades\Route;
use App\Models\{Project, ExactSubProject, SubProjectSyncedStatus};
use Illuminate\Validation\Rule;


class Create extends Component
{
    public $listeners = ['refreshSubProjectModal' => '$refresh', 'updateYear', 'updateSubProject', 'yearUpdated' => 'handleYearUpdated', 'refreshSubProjects'];

    /** @var  SubProjectRepository */
    protected $subProjectRepository;

    /** @var  SubProjectDataRepository */
    protected $subProjectDataRepository;

    /** @var  ProjectDetailRepository */
    protected $projectDetailRepository;

    /** @var  ProjectRepository */
    protected $projectRepository;

    public $loader = false, $project_id;

    public $sub_project, $subProjects, $year, $exactSubProjects=[], $sub_project_id;

    public $selectedYear;


    public function handleYearUpdated($year)
    {
        $this->selectedYear = $year;
        $this->refreshSubProjects();
    }

    public function hydrate()
    {
        $this->setRepository();
    }


    public function mount()
    {
        $this->project_id = Route::current()->parameter('project');
        $this->year = date('Y');
        $this->selectedYear = date('Y');
        $getExactLinkedId = getExactLinkedProjectPerYear($this->project_id, $this->selectedYear);
        $this->exactSubProjects = getExactSubProjects($getExactLinkedId?->exact_project_id);

    }
    public function updateSubProject($value){
        $this->sub_project_id = $value;
        $exactProject = ExactSubProject::where('exact_id', $value)->first();
        if($exactProject){
            $this->sub_project = $exactProject->description;
        }else{
            $this->sub_project = '';
        }
        $this->resetErrorBag("sub_project_id");
        $this->emit('refreshSelectBox');
    }

    public function setRepository()
    {

        $this->subProjectRepository           = app(SubProjectRepository::class);

        $this->subProjectDataRepository       = app(SubProjectDataRepository::class);

        $this->projectDetailRepository        = app(ProjectDetailRepository::class);

        $this->projectRepository              = app(ProjectRepository::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {
        $this->sub_project       = '';
    }

    public function createSubproject()
    {
        $this->emit('refreshSelectBox');
        $validatedData = $this->validate([
            'sub_project_id' => [
                'required',
                Rule::unique('sub_projects', 'exact_id')
                ->where(function ($query) {
                    return $query->where('year', $this->year);
                }),
            ],
        ], [
            'sub_project_id.required' => 'The sub-project field is required.',
            'sub_project_id.unique'   => 'This sub-project already exists for this year.',
        ]);
        $this->loader = true;
        
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
        $projectInformation = Project::where('id',$this->project_id)->first();
        $subProjectExactId = $validatedData['sub_project_id'];
        $baseUrl = config('env.exact_url') ."/read/project/ProjectWBSByProject";
        $params = [
            '$filter' => "Parent eq guid'{$subProjectExactId}'",
            'projectId' => "guid'{$projectInformation->exact_id}'"
        ];
        $apiUrl = $baseUrl . '?' . http_build_query($params);
        $projectWBS = commonCurl($apiUrl, 'GET', []);
        $checkProjectData = $projectWBS->d->results ?? [];
        if(count($checkProjectData)==0){
            createExactSubProjectSchema($projectInformation->exact_id, $subProjectExactId);
            createAutoSyncedValues($_sub_project->id, $this->project_id);
        }else{
            saveExactLineItemsInBudgetApp($this->project_id, $this->year);
        }
        
        $this->emit('closeSubprojectmodal', 'data');
        $this->emit('swal:alert', [
            'title'        => 'Success!',
            'text'         => 'Sub Project Created Successfully!',
            'icon'         => 'success',
            'redirectUrl'  => '/project' . '/' . $this->project_id . '?active_tab=nav-year-' . $this->year,
            'status'       => 'success'
        ]);

        $this->emit('refreshSelectBox');
    }

    public function render()
    {
        $this->setRepository();
        $projectDetail = Project::where('id', $this->project_id)->first();
        $this->subProjects = $this->subProjectDataRepository->wherefirst(['project_id' => $this->project_id, 'sub_project_id' =>  NULL, 'year' => $this->year]);
        $getExactLinkedId = getExactLinkedProjectPerYear($this->project_id, $this->selectedYear);
        $this->exactSubProjects = getExactSubProjects($getExactLinkedId?->exact_project_id);
        return view('livewire.subproject.create');
    }

    public function updateYear($year)
    {
        $this->year = $year;
        $this->subProjects = $this->subProjectDataRepository->wherefirst(['project_id' => $this->project_id, 'sub_project_id' =>  NULL, 'year' => $this->year]);
        $this->emit('refreshSelectBox');
    }

    public function refreshSubProjects()
    {
        $getExactLinkedId = getExactLinkedProjectPerYear($this->project_id, $this->selectedYear);
        if($getExactLinkedId){
            $this->exactSubProjects = getExactSubProjects($getExactLinkedId->exact_project_id);
        }
        $this->emit('refreshSelectBox');
    }

}
