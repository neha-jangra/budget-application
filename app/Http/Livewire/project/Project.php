<?php

namespace App\Http\Livewire\project;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\ProjectRepository;
use App\Repositories\SubProjectDataRepository;
use App\Repositories\SubProjectRepository;
use App\Repositories\ProjectDetailRepository;
use App\Models\Project as Projects;

class Project extends Component
{
    use WithPagination;

    protected $projectRepository, $subProjectDataRepository, $subProjectRepository, $projectDetailRepository, $tableDataExists;
    public $loader = true;
    public $search_by_name;
    public $project_id;
    public $recordsPerPage = 50;
    private $projects = [];

    protected $listeners = ['refreshTable', 'delete'];

    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {
        $this->projectRepository            = app(ProjectRepository::class);

        $this->subProjectDataRepository     = app(SubProjectDataRepository::class);

        $this->subProjectRepository         = app(SubProjectRepository::class);

        $this->projectDetailRepository      = app(ProjectDetailRepository::class);
    }

    public function mount()
    {
        //$this->getProject();
    }

    public function clearInput()
    {
        $this->search_by_name = '';
    }

    public function getProject()
    {
        $this->projects = Projects::where(function ($query) {
            $query->where('project_name', 'like', '%' . $this->search_by_name . '%')
                ->orWhere('project_code', 'like', '%' . $this->search_by_name . '%');
        })->orderBy('project_code', 'ASC')->paginate($this->recordsPerPage);
        $this->tableDataExists = Projects::count() > 0;
        $this->loader = false;
    }


    public function confirmDelete($id)
    {
        $this->project_id = $id;
    }

    public function delete()
    {
        try {
            $this->subProjectDataRepository->Wheredelete(['project_id' => $this->project_id]);

            $this->projectDetailRepository->Wheredelete(['project_id' => $this->project_id]);

            $this->subProjectRepository->Wheredelete(['project_id' => $this->project_id]);

            $this->projectRepository->Wheredelete(['id' => $this->project_id]);

            $this->emit('deleteProjectmodal');

            $this->emit('swal:alert', [

                'title' => 'Success!',

                'text' => 'Project Deleted Successfully!',

                'icon' => 'success',

                'redirectUrl' => '/project',

                'status'  => 'success'
            ]);
        } catch (\Exception $e) {

            $this->emit('swal:alert', [

                'title' => 'Error!',

                'text' => $e->getMessage(),

                'icon' => 'error',

                'status'  => 'error'
            ]);
        }
    }

    public function changeTab($project_id)
    {
        $this->projectRepository->whereUpdate(['id' => $project_id], ['last_tab' => '0']);
    }

    public function render()
    {
        $projects = Projects::with(['subProjectData', 'subProjectData.user', 'subProjectData.user.roles'])->where(function ($query) {
            $query->where('project_name', 'like', '%' . $this->search_by_name . '%')
                ->orWhere('project_code', 'like', '%' . $this->search_by_name . '%');
        })->orderBy('project_code', 'ASC')->paginate($this->recordsPerPage);


        $this->tableDataExists = Projects::count() > 0;

        return view('livewire.project.project', [

            'projects' => $projects,

            'loader' => $this->loader,

            'tableDataExists' => $this->tableDataExists

        ]);
    }
}
