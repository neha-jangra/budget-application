@extends('layouts.app')
@section('content')
    <div class="main-content" role="main">
        <div class="page-header">
            breadcrumbs
            <h6 class="h6 font-medium text-gray-800 mb-4">Role Management</h6>
        </div>
        <div class="content">
            <nav>
                <div class="main-tabbings nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="roles" data-bs-toggle="tab" data-bs-target="#nav-roles"
                        type="button" role="tab" aria-controls="nav-roles" aria-selected="true">Roles</button>
                    <button class="nav-link" id="permissions" data-bs-toggle="tab" data-bs-target="#nav-permissions"
                        type="button" role="tab" aria-controls="nav-permissions" aria-selected="true">Permissions</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-roles" role="tabpanel" aria-labelledby="roles">
                    <div class="content withSideSpacing">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <div>
                                @include('elements.search-bar',['placeholder' => 'Role','model' => 0])
                            </div>
                            <a data-bs-toggle="modal" href="#add_role" class="btn btn-primary theme-btn">
                                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Add new role
                            </a>
                            @include('components.role-management.add-role')
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table theme-table table-hover mb-2">
                            <thead>
                                <tr>
                                    <th scope="col" style="min-width:830px;">Role Name</th>
                                    <th scope="col">Permission</th>
                                    <th scope="col" class="action-toolbar"><div>Action</div></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="clickable-table-cell">
                                        <a data-bs-toggle="modal" href="#edit_role" class="link"></a>
                                        Admin
                                    </td>
                                    <td class="clickable-table-cell">
                                        <a data-bs-toggle="modal" href="#edit_role" class="link"></a>
                                        Editor
                                    </td>
                                    <td class="action-toolbar">
                                        <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                            <li><a data-bs-toggle="modal" href="#edit_role"><img src="{{ asset('images/icons/edit-pencil.svg') }}" alt="edit"></li>
                                            <li><a data-bs-toggle="modal" href="#delete_role"><img src="{{ asset('images/icons/delete-bin.svg') }}" alt="delete"></a></li>
                                        </ul>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="clickable-table-cell">
                                        <a data-bs-toggle="modal" href="#edit_role" class="link"></a>
                                        Finance
                                    </td>
                                    <td class="clickable-table-cell">
                                        <a data-bs-toggle="modal" href="#edit_role" class="link"></a>
                                        Editor
                                    </td>
                                    <td class="action-toolbar">
                                        <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                            <li><a data-bs-toggle="modal" href="#edit_role"><img src="{{ asset('images/icons/edit-pencil.svg') }}" alt="edit"></li>
                                            <li><a data-bs-toggle="modal" href="#delete_role"><img src="{{ asset('images/icons/delete-bin.svg') }}" alt="delete"></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="clickable-table-cell">
                                        <a data-bs-toggle="modal" href="#edit_role" class="link"></a>
                                        Management
                                    </td>
                                    <td>
                                        <a data-bs-toggle="modal" href="#edit_role" class="link"></a>
                                        Editor
                                    </td>
                                    <td class="action-toolbar">
                                        <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                            <li><a data-bs-toggle="modal" href="#edit_role"><img src="{{ asset('images/icons/edit-pencil.svg') }}" alt="edit"></li>
                                            <li><a data-bs-toggle="modal" href="#delete_role"><img src="{{ asset('images/icons/delete-bin.svg') }}" alt="delete"></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="clickable-table-cell">
                                        <a data-bs-toggle="modal" href="#edit_role" class="link"></a>
                                        Project Manager
                                    </td>
                                    <td class="clickable-table-cell">
                                        <a data-bs-toggle="modal" href="#edit_role" class="link"></a>
                                        Editor on their budget
                                    </td>
                                    <td class="action-toolbar">
                                        <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                            <li><a data-bs-toggle="modal" href="#edit_role"><img src="{{ asset('images/icons/edit-pencil.svg') }}" alt="edit"></li>
                                            <li><a data-bs-toggle="modal" href="#delete_role"><img src="{{ asset('images/icons/delete-bin.svg') }}" alt="delete"></a></li>
                                        </ul>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="clickable-table-cell">
                                        <a data-bs-toggle="modal" href="#edit_role" class="link"></a>
                                        Project Member
                                    </td>
                                    <td class="clickable-table-cell">
                                        <a data-bs-toggle="modal" href="#edit_role" class="link"></a>
                                        View only
                                    </td>
                                    <td class="action-toolbar">
                                        <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                            <li><a data-bs-toggle="modal" href="#edit_role"><img src="{{ asset('images/icons/edit-pencil.svg') }}" alt="edit"></li>
                                            <li><a data-bs-toggle="modal" href="#delete_role"><img src="{{ asset('images/icons/delete-bin.svg') }}" alt="delete"></a></li>
                                        </ul>
                                    </td>
                                </tr>  
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-permissions" role="tabpanel" aria-labelledby="permissions">
                    <div class="table-responsive">
                        <table class="table theme-table role-permission-table table-hover mb-2">
                            <thead>
                                <tr>
                                    <th scope="col" style="min-width:666px;">Permissions</th>
                                    <th scope="col" style="text-align:center;">Admin</th>
                                    <th scope="col" style="text-align:center;">Management</th>
                                    <th scope="col" style="text-align:center;">Project Manager</th>
                                    <th scope="col" style="text-align:center;">Project Member</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Create new project
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault4">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Budget revision
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault6">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault7">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault8">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Add new user
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault9">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault10">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault11">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault12">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Add new role
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault13">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault14">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault15">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault16">
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>
                                        Add new donor
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault17">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault18">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault19">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault20">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Master setup
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault21">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault22">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault23">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault24">
                                        </div>
                                    </td>
                                </tr>  
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.role-management.delete')
    @include('components.role-management.edit-role')
@endsection