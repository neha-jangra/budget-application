<div>
    <div class="main-content" role="main">
        <div class="page-header">
            <a href="{{ route('project.index') }}" class="theme-link mb-3">
                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.875 10H3.125" stroke="#667085" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M8.75 4.375L3.125 10L8.75 15.625" stroke="#667085" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Back</span>
            </a>
            <h6 class="text-gray-800 h6 font-medium mb-4">Add New Project</h6>
            @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-error" role="alert">
                {{ session('error') }}
            </div>
            @endif

            @if ($message)
            <div>{{ $message }}</div>
            @endif
        </div>
        <div class="content withSideSpacing">
            <form class="theme-form-card theme-form needs-validation" wire:submit.prevent="store" method="post"
                id="create_project" novalidate>
                <div class="form-field-container">
                    <div class="row align-items-top">
                        <div class="col-lg-6 mb-3">
                            <label class="theme-form-label">Project Code<span class="required">*</span></label>
                            <div wire:ignore>
                                <select
                                    class="js-example-basic-single form-control @error('project_code') is-invalid @enderror donor_select2"
                                    wire:model="project_code" data-placeholder="Select project code">
                                    <option value=""></option>
                                    @foreach ($exactProjects as $project)
                                    <option value="{{ $project->exact_id }}">{{ $project->project_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('project_code')
                            <span class="invalid-feedback alert-error-dropdown" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="theme-form-label">Project Name<span class="required">*</span></label>
                            <input id="project_name" type="text"
                                class="form-control @error('project_name') is-invalid @enderror"
                                wire:model="project_name" value="{{ old('project_name') }}" placeholder="Project title"
                                autocomplete="project_name" autofocus>
                            @error('project_name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div wire:ignore>
                                <label class="theme-form-label">Project Type</label>
                                <select id="project_type"
                                    class="js-example-basic-single select-project-type-create form-control @error('project_type') is-invalid @enderror"
                                    data-minimum-results-for-search="Infinity" wire:model="project_type"
                                    data-placeholder="Select project type">
                                    <option value="">Select project type</option>
                                    <option value="EU">EU</option>
                                    <option value="USAID">USAID</option>
                                    <option value="Government grant">Government grant</option>
                                    <option value="Multilateral grant">Multilateral grant</option>
                                    <option value="Private foundation">Private foundation</option>
                                    <option value="Core grant">Core grant</option>
                                    <option value="Institutional grant">Institutional grant</option>
                                    <option value="Consultancy">Consultancy</option>
                                </select>
                            </div>
                            @error('project_type')
                            <span class="invalid-feedback alert-error-dropdown " role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div wire:ignore>
                                <label class="theme-form-label ">Currency</label>
                                <select id="currency"
                                    class="js-example-basic-single form-control select-project-type-create @error('currency') is-invalid @enderror"
                                    wire:model="currency" data-minimum-results-for-search="Infinity"
                                    data-placeholder="Select currency">
                                    <option value=""></option>
                                    <option value="eur">EUR</option>
                                    <option value="usd">USD</option>
                                    <option value="gbp">GBP</option>
                                </select>
                            </div>
                            @error('currency')
                            <span class="alert-error-dropdown" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="theme-form-label">Contract amount<span class="required">*</span></label>
                            <div class="input-addon right">
                                <input id="budget" type="text"
                                    class="form-control @error('budget') is-invalid @enderror" placeholder="1.000.000,00"
                                    wire:model="budget" autocomplete="budget" min="1" data-type="currency" autofocus>
                                @error('budget')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                                <div class="add-on">
                                    €
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="theme-form-label">Approved indirect rate</label>
                            <div class="input-addon right">
                                <input id="indirect-rate" type="text"
                                    class="form-control @error('indirect_rate') is-invalid @enderror" placeholder="0"
                                    wire:model="indirect_rate" autocomplete="indirect-rate" min="1" autofocus>
                                @error('indirect_rate')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                                <div class="add-on">
                                    %
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div wire:ignore>
                                <label class="theme-form-label">Project donor<span class="required">*</span></label>
                                <select
                                    class=" select2ww form-control create-donot-phone @error('project_donor_id') is-invalid @enderror"
                                    wire:model="project_donor_id" id="project_create_user"
                                    data-placeholder="Select project donor">
                                    <option value="">Select project donor</option>
                                    @foreach ($donors as $donor)
                                    <option value="{{ $donor->id }}">{{ $donor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('project_donor_id')
                            <span class="invalid-feedback alert-error-dropdown " role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="theme-form-label">ECNL Contact<span class="required">*</span></label>
                            <input id="ecnl_contact" type="text"
                                class="form-control @error('ecnl_contact') is-invalid @enderror" placeholder="Max"
                                wire:model="ecnl_contact" autocomplete="ecnl_contact" autofocus>
                            @error('ecnl_contact')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="theme-form-label">Donor Contact Name<span class="required">*</span></label>
                            <input type="text"
                                class="form-control @error('donor_contact_name') is-invalid @enderror"
                                wire:model="donor_contact_name" placeholder="Dominik" autocomplete="off" autofocus>
                            @error('donor_contact_name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="theme-form-label">Donor Email<span class="required">*</span></label>
                            <input id="donor_email" type="email"
                                class="form-control @error('donor_email') is-invalid @enderror"
                                wire:model="donor_email" placeholder="donor@donor.com" autocomplete="donor_email"
                                autofocus>
                            @error('donor_email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-12 col-xl-6 mb-3">
                            <div>
                                <label class="theme-form-label">Donor Contract Number</label>
                                <input type="text" class="form-control" wire:model="donor_contract_number" placeholder="Contract Number" autocomplete="off" autofocus>
                            </div>
                        </div>

                        <div class="col-xl-6 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <label class="theme-form-label">Overall Project Duration (From)<span
                                            class="required">*</span></label>
                                    <div
                                        class="input-addon right date input-box-shadow datepicker2 datepicker_project_duration_from">
                                        <span class="add-on">
                                            <img src="/images/icons/calendar-icon.svg" class="left">
                                        </span>
                                        <input wire:model="project_duration_from" type="text"
                                            class="form-control @error('project_duration_from') is-invalid @enderror  datepicker2 datepicker_project_duration_from"
                                            autocomplete="off" data-provide="datepicker" data-date-autoclose="true"
                                            data-date-format="dd-mm-yyyy" data-date-today-highlight="true"
                                            onchange="this.dispatchEvent(new InputEvent('input'))"
                                            placeholder="dd-mm-yyyy">
                                        @error('project_duration_from')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-6">
                                    <label class="theme-form-label">To<span class="required">*</span></label>
                                    <div
                                        class="input-addon right date right input-box-shadow datepicker2 datepicker_project_duration_to">
                                        <span class="add-on">
                                            <img src="/images/icons/calendar-icon.svg">
                                        </span>
                                        <input type="text"
                                            class="form-control @error('project_duration_to') is-invalid @enderror  datepicker2 datepicker_project_duration_to"
                                            wire:model="project_duration_to" autocomplete="off"
                                            data-provide="datepicker" data-date-autoclose="true"
                                            data-date-format="dd-mm-yyyy" data-date-today-highlight="true"
                                            onchange="this.dispatchEvent(new InputEvent('input'))"
                                            placeholder="dd-mm-yyyy">
                                        @error('project_duration_to')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <label class="theme-form-label">Current Budget Timeline (From)<span class="required">*</span></label>
                                    <div class="input-addon right date datepicker2">
                                        <span class="add-on">
                                            <img src="/images/icons/calendar-icon.svg">
                                        </span>
                                        <input type="text"
                                            class="form-control @error('current_budget_timeline_from') is-invalid @enderror"
                                            wire:model="current_budget_timeline_from"
                                            autocomplete="off"
                                            id="current_budget_timeline_from"
                                            placeholder="dd-mm-yyyy">
                                        @error('current_budget_timeline_from')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="theme-form-label">To<span class="required">*</span></label>
                                    <div class="input-addon right date datepicker2">
                                        <span class="add-on">
                                            <img src="/images/icons/calendar-icon.svg">
                                        </span>
                                        <input type="text"
                                            class="form-control @error('current_budget_timeline_to') is-invalid @enderror"
                                            wire:model="current_budget_timeline_to"
                                            id="current_budget_timeline_to"
                                            autocomplete="off"
                                            id="current_budget_timeline_to"
                                            placeholder="dd-mm-yyyy">
                                        @error('current_budget_timeline_to')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="theme-form-label">Date Prepared</label>
                            <div class="input-addon right date datepicker2 date_prepare_datepicker">
                                <span class="add-on">
                                    <img src="/images/icons/calendar-icon.svg">
                                </span>
                                <input id="date_prepared" type="text"
                                    class="form-control @error('date_prepared') is-invalid @enderror datepicker2 date_prepare_datepicker"
                                    wire:model="date_prepared" autocomplete="off" data-provide="datepicker"
                                    data-date-autoclose="true" data-date-format="dd-mm-yyyy"
                                    data-date-today-highlight="true"
                                    onchange="this.dispatchEvent(new InputEvent('input'))" placeholder="dd-mm-yyyy">
                                @error('date_prepared')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div wire:ignore>
                                <label for="" class="theme-form-label">Confirmed W Finance</label>
                                <select id="confirm_w_finance"
                                    class="js-example-basic-single form-control select-project-type-create"
                                    wire:model="confirm_w_finance" data-minimum-results-for-search="Infinity"
                                    data-placeholder="Select yes or no">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                        </div>
                        <div class="col-lg-12 col-xl-6 mb-3">
                            <label class="theme-form-label">Date Revised</label>
                            <div class="input-addon right date datepicker2 date_revised_datepicker">
                                <span class="add-on">
                                    <img src="/images/icons/calendar-icon.svg">
                                </span>
                                <input id="date_revised" type="text"
                                    class="form-control @error('date_revised') is-invalid @enderror datepicker2 date_revised_datepicker"
                                    wire:model="date_revised" autocomplete="off" data-provide="datepicker"
                                    data-date-autoclose="true" data-date-format="dd-mm-yyyy"
                                    data-date-today-highlight="true"
                                    onchange="this.dispatchEvent(new InputEvent('input'))" placeholder="dd-mm-yyyy">
                                @error('date_revised')
                                <span class="invalid-feedback alert-error-dropdown" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-gray-500 text-sm font-medium d-block mb-3">
                                        Based on the selected timeline, connect an Exact project to each year.
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                @foreach($years as $year)
                                <div class="col-lg-6 mb-3">
                                    <div data-year="{{ $year }}">
                                        <label class="theme-form-label">{{ $year }}<span class="required">*</span></label>
                                        <select id="select-{{ $year }}" class="js-example-basic-single selectProjectCodeJs form-control"
                                            wire:model="selected_codes.{{ $year }}" wire:key="{{ $year }}" data-placeholder="Select project code">
                                            <option value="" selected>Select project code</option>
                                            @foreach($exactProjects as $project)
                                            <option value="{{$project->exact_id}}">{{$project->project_code}}</option>
                                            @endforeach
                                        </select>
                                        @error("selected_codes.$year")
                                        <span class="invalid-feedback alert-error-dropdown">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>

                    </div>
                </div>

                <div class="form-actions d-flex justify-content-sm-end flex-wrap flex-sm-nowrap gap-3 gap-sm-0">
                    <a href="{{ route('project.index') }}"
                        class="btn btn-secondary theme-btn me-12 order-2 order-sm-1">Cancel</a>
                    <button type="submit" class="btn btn-primary theme-btn order-1 order-sm-2">Create new
                        project</button>

                </div>
            </form>
        </div>
    </div>

    <livewire:modal />
</div>