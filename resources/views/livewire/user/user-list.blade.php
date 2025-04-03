<div>
    <div class="page-header">
        <span >@include('elements.breadcrumb',['breadcrumbs' => Breadcrumb()])</span>
        <h6 class="h6 font-medium text-gray-800 mb-20">All Users</h6>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                @include('elements.search-bar',['placeholder' => 'Search users..','model' => 0])
            </div>
            <a type="button" href="/user/create" wire:navigate  class="btn btn-primary theme-btn">
                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Add new user
            </a>
        </div>
    </div>
    <div class="content" wire:init="init">
        @if($loader)
        <livewire:loader />
        @else
        <div class="table-responsive" wire:key="user-{{ time() }}">
            <table class="table theme-table table-hover mb-2">
                <thead>
                    <tr>
                        <th scope="col">User Name</th>
                        <th scope="col">Email address</th>
                        <th scope="col">Role</th>
                        <th scope="col">Created date</th>
                        <th scope="col" class="action-toolbar">
                            <div>Action</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="clickable-table-cell">
                            <a href="{{route('user.edit',['user' =>$user->id])}}" class="link"></a>
                            {{$user->name}}
                        </td>
                        <td class="clickable-table-cell">
                            <a href="{{route('user.edit',['user' =>$user->id])}}" class="link"></a>
                            {{$user->email}}
                        </td>
                        <td class="clickable-table-cell">
                            <a href="{{route('user.edit',['user' =>$user->id])}}" class="link"></a>
                            <?php
                                $user_roles = $user->roles;
                                $excluded_roles = ['Employee', 'Donor', 'Sub-grantee', 'Consultant'];
                                $filtered_roles = $user->roles->filter(function($role) use ($excluded_roles) {
                                    return !in_array($role->title, $excluded_roles);
                                });

                                if ($filtered_roles->isNotEmpty()) {
                                    echo $filtered_roles->last()->title;
                                }
                            ?>
                        </td>
                        <td class="clickable-table-cell">
                            <a href="{{route('user.edit',['user' =>$user->id])}}" class="link"></a>
                            {{dateFormat($user->created_at,'d-m-y')}}
                        </td>
                        <td class="action-toolbar">
                            <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                <li><a href="#" wire:click="redirectToeditpage({{ $user->id }})"><img src="{{ asset('images/icons/edit-pencil.svg') }}" alt="edit" data-bs-tolltip="toggle" title="Edit"></li>
                                <li><a wire:click.prevent="confirmDelete({{$user->id}})" data-bs-toggle="modal" href="#delete_user"><img src="{{ asset('images/icons/delete-bin.svg') }}" alt="delete" data-bs-tolltip="toggle" title="Delete"></a></li>
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
        {{ $users->links('components.pagination') }}
        @endif
    </div>
    @include('components.users.delete')
</div>