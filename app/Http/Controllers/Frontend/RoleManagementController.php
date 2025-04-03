<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Livewire\Livewire;

class RoleManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('role-management.index')->with('livewire', Livewire::mount('rolemanagement.rolemanagement'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create-user')->with('livewire', Livewire::mount('user.create'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('users.edit-user')->with('livewire', Livewire::mount('user.edit',['id' => $id]));
    }
}