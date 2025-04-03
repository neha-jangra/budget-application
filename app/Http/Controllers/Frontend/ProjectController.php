<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Livewire\Livewire;

use App\models\{
    OtherDirectExpense,
    User,
    Project,
    SubProjectData,
};
use App\Repositories\ProjectRepository;

use App\Repositories\CommonRepository;

use App\Constants\RoleConstant;

class ProjectController extends Controller
{


    /** @var  ProjectRepository */
    private $projectRepository;

    /** @var  CommonRepository */
    private $commonRepository;

    public function __construct(CommonRepository $commonRepository, ProjectRepository $projectRepository)
    {
        $this->commonRepository  = $commonRepository;

        $this->projectRepository = $projectRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('projects.index')->with('livewire', Livewire::mount('project.project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('projects.create')->with('livewire', Livewire::mount('project.create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->projectRepository->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('projects.show')->with('livewire', Livewire::mount('project.show'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('projects.edit')->with('livewire', Livewire::mount('project.edit', ['id' => $id]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function Userfetchproject(Request $request)
    {
        $data = $request->all();

        $user     = $this->commonRepository->getUser($data);

        $project  = Project::select('currency')->where(['id' => $data['project_id']])->first();

        return ['user' => $user, 'project' => $project];
    }

    public function Fetchprojectcurrency(Request $request)
    {
        $data = $request->all();
        return  Project::select('currency')->where(['id' => $data['project_id']])->first();
    }


    public function getDonorProject(Request $request)
    {
        $donors = User::whereHas(
            'roles',
            function ($roles) {
                $roles->where('role_id', '=', RoleConstant::DONOR);
            }
        )->orderBy('name')->get();
        return $donors;
    }

    public function getLineitemProject(Request $request)
    {
        $data = $request->all();


        $donors = User::whereHas(
            'roles',
            function ($roles) use ($data) {
                $roles->where('role_id', '=', $data['type_id']);
            }
        )->orderBy('id', 'desc')->get();

        return $donors;
    }

    public function updateLasttab(Request $request)
    {
        $data = $request->all();

        $user     = $this->commonRepository->updateLasttab($data['data']);
    }
}
