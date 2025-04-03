<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Livewire\Livewire;

class LineItemController extends Controller
{

    public $isOpen = false;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('line-items.index')->with('livewire', Livewire::mount('lineitem.lineitem'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('donors.create-donor')->with('livewire', Livewire::mount('donor.create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('donors.edit')->with('livewire', Livewire::mount('donor.edit',['id' => $id]));
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


    /**
     * Show the form for consultant creating a new resource.
     */
    public function createConsultant()
    {
        return view('line-items.create-consultant')->with('livewire', Livewire::mount('lineitem.consultant.create'));
    }

    /**
     * Show the form for editing consultant creating a new resource.
     */
    public function editConsultant()
    {
        return view('line-items.edit-consultant')->with('livewire', Livewire::mount('lineitem.consultant.edit'));
    }

    /**
     * Show the form for subgrantee creating a new resource.
     */
    public function createSubgrantee()
    {
        return view('line-items.create-sub-grantee')->with('livewire', Livewire::mount('lineitem.subgrantee.create'));
    }

    /**
     * Show the form for editing consultant creating a new resource.
     */
    public function editSubgrantee()
    {
        return view('line-items.edit-sub-grantee')->with('livewire', Livewire::mount('lineitem.subgrantee.edit'));
    }

    /**
     * Show the form for employee creating a new resource.
     */
    public function createEmployee()
    {
        return view('line-items.create-employee')->with('livewire', Livewire::mount('lineitem.employee.create'));
    }

    /**
     * Show the form for editing employee creating a new resource.
     */
    public function editEmployee()
    {
        return view('line-items.edit-employee')->with('livewire', Livewire::mount('lineitem.employee.edit'));
    }

    public function openOtherDirectExpenseModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

}
