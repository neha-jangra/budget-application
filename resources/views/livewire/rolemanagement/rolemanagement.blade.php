<div>
    <div class="page-header">
        <span>@include('elements.breadcrumb',['breadcrumbs' => Breadcrumb()])</span>
        <h6 class="h6 font-medium text-gray-800 mb-4">Role Management</h6>
    </div>
    <div class="content">
        <nav>
            <div class="main-tabbings nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link {{ $activeTab == 'tab1' ? 'active' : '' }}" wire:click="redirectPermission('tab1')"  id="roles" data-bs-toggle="tab" data-bs-target="#nav-roles" type="button" role="tab" aria-controls="nav-roles" aria-selected="true">Roles</button>
                <button class="nav-link {{ $activeTab == 'tab2' ? 'active' : '' }}" wire:click="redirectPermission('tab2')" id="permissions" data-bs-toggle="tab" data-bs-target="#nav-permissions" type="button" role="tab" aria-controls="nav-permissions" aria-selected="true">Permissions</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent" wire:init="getRoles">
            @if($loader)
                <livewire:loader />
            @else
                <div class="tab-pane fade {{ $activeTab == 'tab1' ? 'show active' : '' }}" id="nav-roles" role="tabpanel" aria-labelledby="roles">
                    <div class="content withSideSpacing">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <div>
                                @include('elements.search-bar',['placeholder' => 'Search roles..','model' => 0])
                            </div>
                            <a data-bs-toggle="modal" href="#add_role" class="btn btn-primary theme-btn">
                                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Add new role
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="table-responsive" wire:key="roles-{{ time() }}">
                            <table class="table theme-table table-hover mb-2">
                                <thead>
                                    <tr>
                                        <th scope="col" style="max-width:400px;">Role Name</th>
                                        <th scope="col">Permission</th>
                                        <th scope="col" class="action-toolbar">
                                            <div>Action</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles as $role)
                                    <tr>
                                        <td class="clickable-table-cell">
                                            {{$role->title}}
                                        </td>
                                        <td class="clickable-table-cell">
                                            {{getPermission($role->permissions)}}
                                        </td>
                                        <td class="action-toolbar">
                                            <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                                <li><a wire:click="editRole({{$role->id}})" data-bs-toggle="modal" href="#edit_role"><img src="{{ asset('images/icons/edit-pencil.svg') }}" alt="edit" data-bs-tolltip="toogle" title="Edit"></li>
                                                <li><a wire:click="confirmDelete({{$role->id}})" data-bs-toggle="modal" href="#delete_role"><img src="{{ asset('images/icons/delete-bin.svg') }}" alt="delete" data-bs-tolltip="toogle" title="Delete"></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="no-records-td">
                                            <div class="no-records">
                                                <span class="text-gray-50 text-md font-regular">No records found</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $roles->links('components.pagination') }}
                    </div>
                </div>
                <div class="tab-pane fade {{ $activeTab == 'tab2' ? 'show active' : '' }}" id="nav-permissions" role="tabpanel" aria-labelledby="permissions">
                    <form method="post" class="theme-form" wire:submit.prevent="savePermissions">
                        <div>
                            
                            <div class="table-responsive mb-2">
                                <table class="table theme-table role-permission-table theme-form table-hover mb-2">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sticky-column"><div>Permissions</div></th>
                                            @foreach($role_permission as $role)
                                            <th scope="col" style="text-align:center;">{{$role->title}}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $key => $permission)
                                        <tr>
                                            <td class="sticky-column"><div>{{$permission->display_name}}</div></td>
                                            @foreach($role_permission as $key2 => $role)
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"  wire:model.defer="permission_data.{{$role->id}}.{{$permission->id}}" wire:key="permission_{{$key}}_{{$role->id}}" value="{{$role->id}},{{$permission->id}}" id="flexCheckDefault_{{$key}}_{{$role->id}}" {{ ($role->hasPermission($permission->name)) ? 'checked' : '' }} {{ (($permission->name == 'role_management') && $role->id == 1) ? 'disabled' : '' }} {{ $editing ? '' : 'disabled' }}>
                                                </div>
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($editing)
                            <div class="d-flex justify-content-md-end content withSideSpacing">
                                <a  wire:click="cancel" class="btn btn-secondary theme-btn me-12">Cancel</a>
                                <button type="submit" class="btn btn-primary theme-btn">Save changes</button>
                            </div>
                            @else
                            <div class="d-flex justify-content-md-end content withSideSpacing">
                                <a wire:click="edit" class="btn btn-primary theme-btn">Edit</a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            @endif    
        </div>
    </div>
    @include('components.role-management.add-role')
    @include('components.role-management.delete')
    @include('components.role-management.edit-role')
</div>