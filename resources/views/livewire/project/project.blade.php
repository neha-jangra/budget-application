<div>
    <div class="page-header">
        @include('elements.breadcrumb', ['breadcrumbs' => Breadcrumb()])
        <h6 class="h6 font-medium text-gray-800 mb-20">All Projects</h6>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                @include('elements.search-bar', [
                    'placeholder' => 'Search project name, project code..',
                    'model' => 0,
                ])
            </div>
            <a type="button" href="{{ route('project.create') }}" class="btn btn-primary theme-btn">
                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Add new project
            </a>
        </div>
    </div>
    <div class="content" wire:init="getProject">
        @if($loader)
            <livewire:loader />
        @else
            @if($tableDataExists)
                <div class="table-responsive" wire:key="project-{{ time() }}">
                    <table class="table theme-table table-hover mb-2">
                        <thead>
                            <tr>
                                <th scope="col">Project Code</th>
                                <th scope="col">Project Name</th>
                                <th scope="col">Project Donor</th>
                                <th scope="col">Members</th>
                                <th scope="col">Contract amount in EUR</th>
                                <th scope="col">Expiry date</th>
                                <th scope="col">Project duration</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="action-toolbar">
                                    <div>Action</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
            
                            @forelse($projects as $project)
                                <tr wire:click="changeTab({{$project->id}})">
                                    <td class="clickable-table-cell ellipsis" style="max-width: 170px;">
                                        <a href="{{ route('project.show', ['project' => $project->id]) }}"
                                            class="link"></a>
                                        {{ $project->project_code }}
                                    </td>
                                    <td class="clickable-table-cell ellipsis" style="max-width: 170px;">
                                        <a href="{{ route('project.show', ['project' => $project->id]) }}"
                                            class="link"></a>
                                        {{ $project->project_name }}
                                    </td>
                                    <td class="clickable-table-cell">
                                        <a href="{{ route('project.show', ['project' => $project->id]) }}"
                                            class="link"></a>
                                        {{ $project->donor->name }}
                                    </td>
                                    <td class="clickable-table-cell member-column">
                                        <a href="{{ route('project.show', ['project' => $project->id]) }}"
                                            class="link"></a>
                                        <ul class="avatar-group">
                                            @php
                                                $countName = 0;
                                                $usedNames = [];
                                                $countNames = 0;
                                            @endphp
                                            @forelse($project->subProjectData as $key => $subProjectData)
                                                @if(isset($subProjectData->user->name) && $subProjectData->user->roles[0]->id == 3)
                                                    @if (!in_array($subProjectData->user->name, $usedNames) && $countName < 4)
                                                        @php $usedNames[] = $subProjectData->user->name; @endphp
                                                        <li class="avatar toolkitdata" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-title="{{ isset($subProjectData->user->name) ? $subProjectData->user->name : 'NA' }}">
                                                            {{ isset($subProjectData->user->name)
                                                                ? substrString(str_replace(' ', '', $subProjectData->user->userprofile->first_name) . ' ' . str_replace(' ', '', $subProjectData->user->userprofile->last_name))
                                                                : 'NA' }}
                                                        </li>
                                                        @php $countName++ @endphp
                                                    @endif
                                                    @php $countNames++ @endphp
                                                @endif
                                            @empty
                                                
                                            @endforelse
                                            @if($countName == 0)
                                                    {{ 'No members'}}
                                            @endif
                                            @if ($countNames > 4)
                                                <li class="avatar">+{{ $countNames - 4 }}&nbsp;</li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td class="clickable-table-cell">
                                        <a href="{{ route('project.show', ['project' => $project->id]) }}"
                                            class="link"></a>
                                            €{{dutchCurrency($project->budget) }}
                                    </td>
                                    <td class="clickable-table-cell">
                                        <a href="{{ route('project.show', ['project' => $project->id]) }}"
                                            class="link"></a>
                                        {{ dateFormat($project->project_duration_to, 'd-m-y') }}
                                    </td>
                                    <td class="clickable-table-cell">
                                        <a href="{{ route('project.show', ['project' => $project->id]) }}"
                                            class="link"></a>
                                        {{ twoDateDifference($project->project_duration_from, $project->project_duration_to) }}
                                    </td>
                                    <td class="clickable-table-cell">
                                        <a href="{{ route('project.show', ['project' => $project->id]) }}"
                                            class="link"></a>
                                        <div class="status {{ $project->status == 1 ? 'active' : 'inactive' }}">
                                            {{ $project->status == 1 ? 'Active' : 'Inactive' }} </div>
                                    </td>
                                    <td class="action-toolbar">
                                        <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                            <li><a href="{{ route('project.show', ['project' => $project->id]) }}"><img
                                                        src="{{ asset('images/icons/view-eye.svg') }}" alt="view" data-bs-tolltip="toggle" title="View"></a>
                                            </li>
                                            <!-- <li><a href="{{ route('project.edit', ['project' => $project->id]) }}"><img src="{{ asset('images/icons/edit-pencil.svg') }}" alt="edit"></li> -->
                                            <li><a data-bs-toggle="modal" href="#delete_project"
                                                    wire:click="confirmDelete({{ $project->id }})"><img
                                                        src="{{ asset('images/icons/delete-bin.svg') }}"
                                                        alt="delete" data-bs-tolltip="toggle" title="Delete"></a></li>
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
                <div>{{ $projects->links('components.pagination') }}</div>
                @else
                <div class="content withSideSpacing">
                    <div class="no-records-without-table">
                        <span class="text-gray-50 text-md font-regular">You don't have any projects listed yet!</span>
                    </div>
                </div>
            @endif
        @endif

        @include('components.projects.delete')
    </div>
</div>
