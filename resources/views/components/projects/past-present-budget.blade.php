<div class="tab-pane fade {{ $activeClass }}" id="nav-year-{{ $tabYear }}" role="tabpanel"
    aria-labelledby="tabYear{{ $tabYear }}" wire:ignore.self>
    <div class="details-table">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
            <h2 class="text-gray-800 text-xl font-semibold mb-0">
                {{ $tabTitle }}
                @if(!$isSyncable && !$subprojects->project->isEmpty())
                <div class="alert alert-warning text-sm mt-2">
                    ⚠️ Warning: Can’t load this project due to a structure mismatch with the Exact environment.<br>
                    Fix in Exact: Go to the project → select the WBS tab → check and align the structure.
                </div>
                @endif
            </h2>


            <div class="d-flex align-items-center flex-wrap gap-3">
                <a type="button" data-bs-toggle="modal" wire:click="$emit('updateYear', {{$tabYear}})"
                    data-bs-target="#sub-project-modal"
                    class=" btn btn-primary theme-btn">
                    <svg class="me-2" width="20" height="20" viewBox="0 0 20 20"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Add sub project
                </a>
            </div>
        </div>
        @if ($subprojects->project->isEmpty())
        <h2 class="text-gray-800 text-xl font-semibold mb-0">
            <div class="text-sm alert alert-info">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                A sub-project is required to allocate the budget. Please add a sub-project to proceed.
            </div>
        </h2>
        @elseif (!$subprojects->project->isEmpty())

        <ul class="nav nav-tabs tabbing-outlined" id="myTab{{ $tabYear }}" role="tablist">
            <li class="nav-item sub-project-result" role="presentation" wire:ignore.self>
                <button
                    class="nav-link change-sub-project-tab active"
                    id="0{{ $tabYear }}" data-bs-toggle="tab" data-bs-target="#home{{ $tabYear }}" type="button"
                    role="tab" aria-controls="home{{ $tabYear }}" aria-selected="true" wire:ignore.self>All
                    Projects </button>
            </li>
            @foreach ($subprojects->project as $key => $subproject)
            <li class="nav-item serach-result-nav sub-project-result" role="presentation" wire:ignore.self>
                <button
                    class="nav-link change-sub-project-tab"
                    id="{{ $subproject->id }}{{ $tabYear }}" data-bs-toggle="tab"
                    data-bs-target="#{{ $tabYear }}_{{ $key }}_profile" type="button" role="tab"
                    aria-controls="profile"
                    aria-selected="false" wire:ignore.self>{{ $subproject->sub_project_name }}</button>
            </li>
            @endforeach
        </ul>
        @include('components.projects.with-sub-projects-data')
        @endif
    </div>
</div>