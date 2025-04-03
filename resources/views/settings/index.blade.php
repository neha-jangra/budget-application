@extends('layouts.app')
@section('title', 'Settings')
@section('content')
    <div class="main-content" role="main">
        <div class="page-header">
            <span>@include('elements.breadcrumb',['breadcrumbs' => Breadcrumb()])</span>
            <h6 class="h6 font-medium text-gray-800 mb-4">Settings</h6>
        </div>
        <div class="content">
            <nav>
                <div class="main-tabbings nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link {{ $activeTab === 'edit_profile' ? 'active' : '' }}" id="edit_profile" data-bs-toggle="tab" data-bs-target="#nav-edit-profile" type="button" role="tab" aria-controls="nav-edit-profile" aria-selected="true" onclick="updateTab('edit_profile')">Edit Profile</button>
                    <button class="nav-link {{ $activeTab === 'password' ? 'active' : '' }}" id="password" data-bs-toggle="tab" data-bs-target="#nav-password" type="button" role="tab" aria-controls="nav-password" aria-selected="false"  onclick="updateTab('password')">Password</button>
                </div>
            </nav>
           <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade {{ $activeTab === 'edit_profile' ? 'active show' : '' }}" id="nav-edit-profile" role="tabpanel" aria-labelledby="edit_profile">
                    <div class="content withSideSpacing">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-10 mx-auto">
                                    @livewire('settings.edit-profile')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade  {{ $activeTab === 'password' ? 'active show' : '' }}" id="nav-password" role="tabpanel" aria-labelledby="password">
                    <div class="content withSideSpacing">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-10 mx-auto">
                                    @livewire('settings.change-password')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection