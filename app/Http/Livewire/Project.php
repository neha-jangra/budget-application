<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Repositories\ProjectRepository;

use App\Models\Project as Products;

use App;

class Project extends Component
{
    /** @var  ProjectRepository */
    protected $projectRepository;

    public $projects, $loader = true;

    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {
        $this->projectRepository = App::make(ProjectRepository::class);
    }

    public function getProducts()
    {


        $this->projects   = $this->projectRepository->all();

        $this->loader = false;
    }

    public function render()
    {
        return view('livewire.project', [
            'projects' => $this->projects,
        ]);
    }
}
