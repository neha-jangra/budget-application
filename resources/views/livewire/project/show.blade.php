@php
$yearInformation = calculateYears($projectdetail->current_budget_timeline_from, $projectdetail->current_budget_timeline_to);
@endphp
<div>
    <div class="main-content">
        <div class="page-header">
            <div class="breadcrumb d-flex align-items-center mb-3 flex-warp flex-md-nowrap gap-2 gap-md-0">
                <img src="{{ asset('images/icons/breadcrumb-home.svg') }}" alt="home-icon">
                <img src="{{ asset('images/icons/chevron-right.svg') }}" alt=" auth-logo" class="custom-mx-2px">
                <a href="{{ route('project.index') }}"><span
                        class="d-inline-block text-sm font-semibold px-2 py-1 text-gray-600">Project</span></a>
                <img src="{{ asset('images/icons/chevron-right.svg') }}" alt="auth-logo" class="custom-mx-2px">
                <span
                    class="d-inline-block text-sm font-semibold px-2 py-1 text-gray-600 active-page">{{ $projectdetail->project_name }}</span>
            </div>
        </div>
        <div class="content project-details mt-2 on-load-project-id" data-project-id="{{ $projectdetail->id }}">
            <nav>
                <div class="main-tabbings nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link {{ ($activeTab!='comments') ? 'active' : '' }}" id="overview" data-bs-toggle="tab" data-bs-target="#nav-overview"
                        type="button" role="tab" aria-controls="nav-overview" onclick="updateTab('nav-year-{{ date('Y') }}')"
                        aria-selected="true">Overview</button>
                    <button class="nav-link {{ ($activeTab=='comments') ? 'active' : '' }}" id="comments" data-bs-toggle="tab" data-bs-target="#nav-comments"
                        type="button" role="tab" aria-controls="nav-comments" onclick="updateTab('comments')"
                        aria-selected="false">
                        Comments
                        <span id="unread-comments-count" class="unread-comments-count d-none"></span>
                    </button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade  {{ ($activeTab!='comments') ? 'show active' : '' }}" id="nav-overview" role="tabpanel" aria-labelledby="overview">
                    <div class="content withSideSpacing">
                        <div class="basic-details">
                            <div class="accordion overview-main-card" id="accordion-core-support">
                                <div class="accordion-item bordered-card mb-24">
                                    <h2 class="accordion-header" id="core-support-accordion-headingOne">
                                        <div
                                            class="card-hdr d-flex w-100 justify-content-between align-items-center gap-2 flex-wrap flex-lg-nowrap">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#core-support-accordion" aria-expanded="true"
                                                aria-controls="core-support-accordion">
                                                <h3 class="mb-0">
                                                    <span>
                                                        <svg width="24" height="24" viewBox="0 0 24 24"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z"
                                                                stroke="#667085" stroke-width="1.5"
                                                                stroke-miterlimit="10" />
                                                            <path d="M15.375 10.875L12 14.625L8.625 10.875"
                                                                stroke="#667085" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                    {{ $projectdetail->project_name }}
                                                </h3>
                                            </button>
                                            <div
                                                class="d-flex align-items-center gap-12 edit-project-btn flex-md-shrink-0 flex-wrap flex-lg-nowrap">
                                                {{-- <form
                                                    class="theme-form currency-conversion-form d-flex align-items-center gap-12 flex-wrap flex-lg-nowrap">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <label class="theme-form-label">Currency:</label>
                                                        <select id="projectCurrency" type="text"
                                                            class="currency-select js-example-basic-single form-control @error('currency') is-invalid @enderror"
                                                            data-minimum-results-for-search="Infinity">
                                                            <option value="EUR">EUR</option>
                                                            <option value="USD">USD</option>
                                                            <option value="GBP">GBP</option>
                                                        </select>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <label class="theme-form-label flex-shrink-0">XE rate:</label>
                                                        <input id="XE_rate" type="text"
                                                            class="xe-rate-input form-control @error('XE_rate') is-invalid @enderror">
                                                    </div>
                                                </form> --}}
                                                <div class="flex-shrink-0">
                                                    <a href="{{ route('project.edit', ['project' => $project_id]) }}"
                                                        class="btn btn-secondary edit-btn">
                                                        <svg class="me-2" width="20" height="20"
                                                            viewBox="0 0 20 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M7.5 16.8749H3.75C3.58424 16.8749 3.42527 16.8091 3.30806 16.6919C3.19085 16.5747 3.125 16.4157 3.125 16.2499V12.7577C3.12472 12.6766 3.14044 12.5962 3.17128 12.5211C3.20211 12.446 3.24745 12.3778 3.30469 12.3202L12.6797 2.94524C12.7378 2.88619 12.8072 2.83929 12.8836 2.80728C12.9601 2.77527 13.0421 2.75879 13.125 2.75879C13.2079 2.75879 13.2899 2.77527 13.3664 2.80728C13.4428 2.83929 13.5122 2.88619 13.5703 2.94524L17.0547 6.42962C17.1137 6.48777 17.1606 6.55709 17.1927 6.63355C17.2247 6.71 17.2411 6.79205 17.2411 6.87493C17.2411 6.95781 17.2247 7.03987 17.1927 7.11632C17.1606 7.19277 17.1137 7.26209 17.0547 7.32024L7.5 16.8749Z"
                                                                stroke="#667085" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M16.875 16.875H7.5" stroke="#667085"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M10.625 5L15 9.375" stroke="#667085"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </h2>
                                    <div id="core-support-accordion" class="accordion-collapse collapse show"
                                        aria-labelledby="core-support-accordion-headingOne">
                                        <div class="accordion-body main-card-body">
                                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4">
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Project
                                                                code</span>
                                                            <p class=" text-gray-800 text-sm font-bold mb-0 text-break">
                                                                {{ $projectdetail->project_code }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Project
                                                                name</span>
                                                            <p
                                                                class=" text-gray-800 text-sm font-bold mb-0 text-break">
                                                                {{ $projectdetail->project_name }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Project
                                                                type</span>
                                                            <p
                                                                class=" text-gray-800 text-sm font-bold mb-0 text-break">
                                                                {{ $projectdetail->project_type }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">

                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Project
                                                                donor</span>
                                                            <p
                                                                class=" text-gray-800 text-sm font-bold mb-0 text-break">
                                                                {{ $projectdetail->donor_contact_name ? $projectdetail->donor->name : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">ECNL
                                                                Contact</span>
                                                            <p
                                                                class=" text-gray-800 text-sm font-bold mb-0 text-break">
                                                                {{ $projectdetail->ecnl_contact }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Donor
                                                                email</span>
                                                            <p
                                                                class=" text-gray-800 text-sm font-bold mb-0 text-break">
                                                                {{ $projectdetail->donor->email ? $projectdetail->donor->email : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Donor
                                                                Contract Number</span>
                                                            <p
                                                                class=" text-gray-800 text-sm font-bold mb-0 text-break">
                                                                {{ $projectdetail->donor_contract_number ? $projectdetail->donor_contract_number : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Project
                                                                duration</span>
                                                            <p
                                                                class=" text-gray-800 text-sm font-bold mb-0 text-break">
                                                                {{ twoDateDifference($projectdetail->project_duration_from, $projectdetail->project_duration_to) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Budget
                                                                timeline</span>
                                                            <p
                                                                class=" text-gray-800 text-sm font-bold mb-0 text-break">
                                                                {{ dateFormat($projectdetail->current_budget_timeline_from, 'dmy') }}
                                                                -
                                                                {{ dateFormat($projectdetail->current_budget_timeline_to, 'dmy') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Confirmed
                                                                W finance</span>
                                                            <p
                                                                class="text-gray-800 text-sm font-bold mb-0 text-break text-capitalize">
                                                                {{ $projectdetail->confirm_w_finance ? $projectdetail->confirm_w_finance : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Contract
                                                                amount in EUR</span>
                                                            <p
                                                                class="text-gray-800 text-sm font-bold mb-0 text-break text-capitalize currencyConvertionJs" data-original-amount="{{ $projectdetail->budget }}">
                                                                €{{ $projectdetail->budget ? dutchCurrency($projectdetail->budget) : '0' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="single-detail-clmn">
                                                        <div>
                                                            <span
                                                                class="text-gray-500 text-xs font-semibold d-block mb-1">Approved
                                                                indirect rate</span>
                                                            <p class="text-gray-800 text-sm font-bold mb-0 text-break">
                                                                {{ $projectdetail->indirect_rate ?? 0 }}%
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('livewire.project.project_matrix', [
                            'project_id' => $projectdetail->id,
                            'sub_project' => '',
                            'project_detail' => $projectdetail,
                            'sub_project_data' => 0,
                            'tabYear' =>NULL,
                            ])
                        </div>
                        <div class="sub-project">
                            <div class="bordered-card mb-24 pt-3">
                                <nav>
                                    <div class="main-tabbings nav nav-tabs mb-0" id="nav-tab" role="tablist" wire:ignore.self>
                                        @foreach ($yearInformation as $year)
                                        @php
                                        $parentTab = "nav-year-".$year['year'];
                                        $isActive = ($activeTab == $parentTab || (!isset($activeTab) && $year['year'] == date('Y')));
                                        @endphp
                                        <button class="nav-link {{ $isActive ? 'active' : '' }}"
                                            id="tabYear{{$year['year']}}" data-bs-toggle="tab"
                                            data-bs-target="#{{ $parentTab }}" type="button" role="tab"
                                            wire:click="updateSelectedYear('{{ $year['year'] }}')"
                                            aria-controls="{{ $parentTab }}" aria-selected="true"
                                            onclick="updateTab('{{ $parentTab }}')" wire:ignore.self>
                                            {{$year['year']}}
                                        </button>

                                        @if($year['year'] == date('Y'))
                                        <button class="nav-link {{ ($activeTab=='nav-revision-for-current-year') ? 'active' : '' }}" id="RevisionForCurrentYear" data-bs-toggle="tab"
                                            data-bs-target="#nav-revision-for-current-year" type="button"
                                            role="tab" aria-controls="nav-revision-for-current-year" onclick="updateTab('nav-revision-for-current-year')"
                                            aria-selected="true" wire:ignore.self>Revision for Current Year({{ date('Y') }})</button>
                                        @endif
                                        @endforeach
                                    </div>
                                </nav>

                                <div class="tab-content" id="nav-tabContent" wire:ignore.self>
                                    @foreach ($yearInformation as $year)
                                    @php
                                        $parentTab = "nav-year-".$year['year'];
                                        $activeClass = ($activeTab == $parentTab) ? 'show active' : '';
                                        $title = ($year['year'] == date('Y')) ? 'Current Year(' .$year['year'].')' : (($year['year'] < date('Y')) ? 'Historical Data(' .$year['year'].')' : 'Future Budget(' .$year['year'].')');
                                    @endphp
                                    @if ($year['year']==date('Y'))
                                        {{-- Include component for the current year --}}
                                        @include('components.projects.past-present-budget', [ 'tabYear'=> $year['year'],
                                            'activeClass' => $activeClass,
                                            'yearStartDate' => $year['startDate'],
                                            'yearEndDate' => $year['endDate'],
                                            'fromMonth' => $year['fromMonth'],
                                            'toMonth' => $year['endMonth'],
                                            'tillTodayTitle' => 'Current Year Expenses',
                                            'approvedBudget' => 'Current Year Budget',
                                            'leftoverTitle' => 'Remaining Balance',
                                            'tabTitle' => $title,
                                            'projectTabData' => getProjectsTabsData($year['year'], $projectdetail->id),
                                            'all_projects'=> allProjects($year['year'], $projectdetail->id),
                                            'isFuture' => $year['isFutureYear'],
                                            'subprojects' => getTabProjects($projectdetail->id, $year['year']),
                                            'isSyncable' => checkIfDataSyncedOrNot($projectdetail->id, $year['year'])
                                        ])

                                        {{-- Revision for the current year tab --}}
                                        @php
                                            $revisionTab = "nav-revision-for-current-year";
                                            $revisionActiveClass = ($activeTab == $revisionTab) ? 'show active' : '';
                                        @endphp

                                        @include('components.projects.revised-budget', [
                                            'tabYear' => $year['year'],
                                            'activeClass' => $revisionActiveClass,
                                            'yearStartDate' => $year['startDate'],
                                            'yearEndDate' => $year['endDate'],
                                            'fromMonth' => $year['fromMonth'],
                                            'approvedBudget' => 'Current Year Budget',
                                            'toMonth' => $year['endMonth'],
                                            'projectTabData' => getProjectsTabsData($year['year'], $projectdetail->id),
                                            'all_projects'=> allProjects($year['year'], $projectdetail->id),
                                            'isFuture' => $year['isFutureYear'],
                                            'subprojects' => getTabProjects($projectdetail->id, $year['year'])
                                        ])

                                    @else
                                        {{-- Include component for past or future years --}}
                                        @include('components.projects.past-present-budget', [
                                            'tabYear' => $year['year'],
                                            'activeClass' => $activeClass,
                                            'yearStartDate' => $year['startDate'],
                                            'yearEndDate' => $year['endDate'],
                                            'fromMonth' => $year['fromMonth'],
                                            'toMonth' => $year['endMonth'],
                                            'subprojects' => getTabProjects($projectdetail->id, $year['year']),
                                            'tillTodayTitle' => $year['year'] < date('Y') ? 'Prev Year Expenses' : 'Future Year Expenses' , 'approvedBudget'=> $year['year'] < date('Y') ? 'Prev Year Budget' : 'Future Year Budget' , 'leftoverTitle'=> 'Remaining Balance',
                                            'tabTitle' => $title,
                                            'projectTabData' => getProjectsTabsData($year['year'], $projectdetail->id),
                                            'all_projects'=> allProjects($year['year'], $projectdetail->id),
                                            'isFuture'=> $year['isFutureYear'],
                                            'isSyncable' => checkIfDataSyncedOrNot($projectdetail->id, $year['year'])
                                        ])
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade {{ ($activeTab=='comments') ? 'show active' : '' }}" id="nav-comments" role="tabpanel" aria-labelledby="comments" wire:ignore>
                    <div class="content withSideSpacing comment-section">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 mx-auto">
                                    <form class="theme-form-card theme-form" enctype="multipart/form-data">
                                        <div class="form-field-container" id="chatWindow">
                                            <div class="row">
                                                <div class="col-md-12 mb-5 commentsList">
                                                    @foreach ($comments as $comment)
                                                    @if ($comment->parent_id == null)
                                                    <div class="d-flex flex-start mb-4 comment-container" id="comment-{{ $comment->id }}" data-delete-id="{{ $comment->id }}">
                                                        <div class="rounded-circle me-12">
                                                            <div class="username-initials">{{ nameInitialByName($comment->user->name) }}</div>
                                                        </div>
                                                        <div class="flex-grow-1 flex-shrink-1">
                                                            <div>
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <p class="mb-0 text-md font-semibold text-gray-800">{{ $comment->user->name }} <span class="font-regular text-gray-500">{{ ($comment->user->id == auth()->user()->id) ? '(You)' : '' }}</span></p>
                                                                    <p class="mb-0 text-sm font-regular text-gray-500 comment-timestamp" data-utc-timestamp="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->format('M d Y \a\t h:i a') }}</p>
                                                                </div>
                                                                <div class="comment-listing-text">
                                                                    <div class="text-md font-regular text-gray-800 mb-0 comment-content message-text-css">{!! $comment->content !!}</div>
                                                                    <div class="comment-attachment" data-comment-id="{{  $comment->id }}">
                                                                        @foreach ($comment->attachments as $attachment)
                                                                        <div class="comment-attachment-file" data-file-id="{{ $attachment->id }}" data-comment-id="{{  $comment->id }}">
                                                                            @if (in_array($attachment->file_type, ['jpeg', 'png', 'gif', 'webp', 'jpg', 'bmp']))
                                                                            <a href="{{ $attachment->file_path }}" data-fancybox data-file-id="{{ $attachment->id }}">
                                                                                <img src="{{ $attachment->file_path }}" width="100" height="100" alt="Picture" class="object-cover" />
                                                                            </a>
                                                                            <div class="action-btns">
                                                                                <a href="{{ $attachment->file_path }}" class="file-download" download="file">
                                                                                    <img src="/images/icons/file-download.svg">
                                                                                </a>
                                                                                <a class="comment-attachment-link" data-fancybox href="{{ $attachment->file_path }}">
                                                                                    <span class="reviewImage">
                                                                                        <img src="/images/icons/file-view.svg">
                                                                                    </span>
                                                                                </a>
                                                                            </div>
                                                                            @else
                                                                            <a href="{{ $attachment->file_path }}" data-fancybox data-file-id="{{ $attachment->id }}">
                                                                                <img src="/images/file.svg" width="100" height="100" alt="File" />
                                                                            </a>
                                                                            <div class="action-btns">
                                                                                <a href="{{ $attachment->file_path }}" class="file-download" download="file">
                                                                                    <img src="/images/icons/file-download.svg">
                                                                                </a>
                                                                                <a class="comment-attachment-link" data-fancybox href="{{ $attachment->file_path }}">
                                                                                    <span class="reviewImage">
                                                                                        <img src="/images/icons/file-view.svg">
                                                                                    </span>
                                                                                </a>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-start commentActionDiv comment-action-div-css">
                                                                    @if (Auth::user()->id==$comment->user_id)
                                                                    <a href="#!" class="me-3 text-decoration-none">
                                                                        <p class="mb-0 text-xs font-medium text-gray-500 edit-comment" data-comment-id="{{ $comment->id }}">Edit</p>
                                                                    </a>
                                                                    @endif
                                                                    <a href="#!" class="me-3 text-decoration-none reply-comment" data-comment-id="{{ $comment->id }}">
                                                                        <p class="mb-0 text-xs font-medium text-gray-500">Reply</p>
                                                                    </a>
                                                                    @if (Auth::user()->id==$comment->user_id)
                                                                    <a href="#!" class="me-3 text-decoration-none">
                                                                        <p class="mb-0 text-xs font-medium text-gray-500 delete-comment" data-comment-id="{{ $comment->id }}">Delete</p>
                                                                    </a>
                                                                    @endif
                                                                </div>

                                                                <div id="replies-{{ $comment->id }}">
                                                                    @if ($comment->replies->count() > 0)
                                                                    @foreach ($comment->replies as $reply)
                                                                    <div class="d-flex flex-start mt-4 reply-container" id="reply-{{ $reply->id }}" data-delete-id="{{ $reply->id }}">
                                                                        <div class="rounded-circle me-12">
                                                                            <div class="username-initials">{{ nameInitialByName($reply->user->name) }}</div>
                                                                        </div>
                                                                        <div class="flex-grow-1 flex-shrink-1">
                                                                            <div>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0 text-md font-semibold text-gray-800">{{ $reply->user->name }} <span class="font-regular text-gray-500">{{ ($reply->user->id == auth()->user()->id) ? '(You)' : '' }}</span></p>
                                                                                    <p class="mb-0 text-sm font-regular text-gray-500 comment-timestamp" data-utc-timestamp="{{ $reply->created_at->toIso8601String() }}">{{ $reply->created_at->format('M d Y \a\t h:i a') }}</p>

                                                                                </div>
                                                                                <div class="text-md font-regular text-gray-800 comment-listing-text">
                                                                                    {!! $reply->content !!}

                                                                                    <div class="comment-attachment" data-comment-id="{{ $reply->id }}">
                                                                                        @foreach ($reply->attachments as $attachment)
                                                                                        <div class="comment-attachment-file" data-file-id="{{ $attachment->id }}" data-comment-id="{{  $reply->id }}">
                                                                                            @if (in_array($attachment->file_type, ['jpeg', 'png', 'gif', 'webp', 'jpg', 'bmp']))
                                                                                            <a href="{{ $attachment->file_path }}" data-fancybox data-file-id="{{ $attachment->id }}">
                                                                                                <img src="{{ $attachment->file_path }}" width="100" height="100" alt="Picture" class="object-cover" />
                                                                                            </a>
                                                                                            <div class="action-btns">
                                                                                                <a href="{{ $attachment->file_path }}" class="file-download" download="file">
                                                                                                    <img src="/images/icons/file-download.svg">
                                                                                                </a>
                                                                                                <a class="comment-attachment-link" data-fancybox href="{{ $attachment->file_path }}">
                                                                                                    <span class="reviewImage">
                                                                                                        <img src="/images/icons/file-view.svg">
                                                                                                    </span>
                                                                                                </a>
                                                                                            </div>
                                                                                            @else
                                                                                            <a href="{{ $attachment->file_path }}" data-fancybox data-file-id="{{ $attachment->id }}">
                                                                                                <img src="/images/file.svg" width="100" height="100" alt="File" />
                                                                                            </a>
                                                                                            <div class="action-btns">
                                                                                                <a href="{{ $attachment->file_path }}" class="file-download" download="file">
                                                                                                    <img src="/images/icons/file-download.svg">
                                                                                                </a>
                                                                                                <a class="comment-attachment-link" data-fancybox href="{{ $attachment->file_path }}">
                                                                                                    <span class="reviewImage">
                                                                                                        <img src="/images/icons/file-view.svg">
                                                                                                    </span>
                                                                                                </a>
                                                                                            </div>
                                                                                            @endif
                                                                                        </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex justify-content-start commentActionDiv comment-action-div-css">
                                                                                    {{-- <a href="#!" class="me-3 text-decoration-none">
                                                                                                        <p class="mb-0 text-xs font-medium text-gray-500 edit-comment" data-comment-id="{{ $reply->id }}">Edit</p>
                                                                                    </a> --}}
                                                                                    @if (Auth::user()->id==$reply->user_id)
                                                                                    <a href="#!" class="me-3 text-decoration-none">
                                                                                        <p class="mb-0 text-xs font-medium text-gray-500 delete-comment" data-comment-id="{{ $reply->id }}">Delete</p>
                                                                                    </a>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                        <div id="attachment-preview" class="attachment-preview-block px-4 pt-2 bg-primary-50" wire:ignore></div>
                                        <div class="position-relative" wire:ignore>
                                            <div class="hidden-content" style="display: none;"></div>
                                            <input type="hidden" id="tagged_users" name="tagged_users">
                                            <input type="hidden" id="project_id" name="project_id" value="{{ $projectdetail->id }}">
                                            <input type="hidden" id="auth_id" name="auth_id" value="{{ Auth::user()->id }}">
                                            <div class="form-control message-input" contenteditable="true" placeholder="Comment or type '@' to mention"></div>
                                            <div class="message-input-icons-section">
                                                <div class="message-file-input-container">
                                                    <input type="file" name="message-file-input" id="message_file_input" class="message-file-input" multiple wire:ignore />
                                                    <label class="message-file-input-label" for="message_file_input">
                                                        <img src="/images/icons/paperclip.svg" alt="Upload">
                                                    </label>
                                                </div>
                                                <button type="button" id="send-message-btn" class="send-message-btn">
                                                    <img src="/images/icons/send-messeage.svg" alt="Send">
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind('[data-fancybox]', {});
    </script>
    <script>
        $(document).ready(function() {
            var chatWindow = $('#chatWindow');
            chatWindow.scrollTop(chatWindow[0].scrollHeight);
        });
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.comment-timestamp').forEach(function(element) {
                const utcTimestamp = element.dataset.utcTimestamp;
                const localDate = new Date(utcTimestamp);

                // Format the date manually with leading zeros
                const formattedTimestamp = localDate.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                }).replace(',', '');

                element.textContent = formattedTimestamp;
            });
        });
        $(document).ready(function() {
            // Function to scroll the chat window to the bottom
            function scrollChatWindow() {
                var chatWindow = $('#chatWindow');
                chatWindow.scrollTop(chatWindow[0].scrollHeight);
            }

            // Attach event listener to the 'send-message-btn' button
            $('#send-message-btn').on('click', function() {
                // Scroll the chat window to the bottom after a new message is sent
                scrollChatWindow();
            });

            // Scroll the chat window to the bottom on page load
            scrollChatWindow();
        });
    </script>
    <div wire:ignore.self>
        @livewire('subproject.create', [], key('subproject-create-' . time()))
    </div>

    @include('components.projects.edit-sub-project')
    @include('components.projects.delete-sub-project')
    @include('components.projects.delete-item')
    @include('components.projects.delete-all-item')
    <livewire:line-item-modal />
</div>