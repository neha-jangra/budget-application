<div>
    <div class="page-header">
        <a href="/line-item?active_tab=tab3" class="theme-link mb-3">
            <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.875 10H3.125" stroke="#667085" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.75 4.375L3.125 10L8.75 15.625" stroke="#667085" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Back to all employees</span>
        </a>
        <h6 class="text-gray-800 h6 font-medium mb-4">Add New Employee</h6>
    </div>
    <div class="content withSideSpacing">
        <form class="theme-form-card theme-form" method="post" wire:submit.prevent="store">
            <div class="form-field-container">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Select employee<span class="required">*</span></label>
                        <div wire:ignore>
                            <select
                                class="js-example-basic-single form-control @error('employee') is-invalid @enderror donor_select2"
                                wire:model="employee" data-placeholder="Select employee">
                                <option value=""></option>
                                @foreach ($exactEmployees as $employee)
                                <option value="{{ $employee->exact_id }}">{{ $employee->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('employee')
                        <span class="invalid-feedback alert-error-dropdown" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="olivia@testmail.com" wire:model="email" autocomplete="off" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                        @if ($userExists)
                        <span class="valid-feedback" style="display:{{ $userExists ? 'block' : 'none' }};">
                            This user already exists. Upon saving, they will also be designated as an employee.
                        </span>
                        @endif

                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Phone Number</label>
                        <div wire:ignore>
                            <input id="phone" type="tel" class="form-control donor_create_phone w-100" placeholder="1231231234" wire:model="phone_number" autocomplete="off" autofocus wire:keydown.escape="updateOldPhoneNumber" wire:blur="updateOldPhoneNumber">
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Position</label>
                        <input id="company" type="text" class="form-control" placeholder="Position" wire:model="position" autocomplete="off" autofocus>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Daily Rate<span class="required">*</span></label>
                        <div class="position-relative daily-rate-input">
                            <div class="position-absolute currency-select2" wire:ignore>
                                <select class="js-example-basic-single w-100" data-minimum-results-for-search="Infinity" wire:model="country_rate">
                                    <option value="eur">EUR</option>
                                    <option value="usd">USD</option>
                                    <option value="gbp">GBP</option>
                                </select>
                            </div>
                            <input id="rate" class="form-control @error('rate') is-invalid @enderror" placeholder="150" autocomplete="rate" wire:model="rate" data-type="currency" autofocus>
                            @error('rate')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions d-flex justify-content-md-between flex-wrap flex-md-nowrap gap-2 gap-md-0">
                <div class="d-flex algin-items-center flex-wrap flex-md-nowrap gap-3 gap-md-0 order-1 order-md-2 ms-auto">
                    <a href="/line-item?active_tab=tab3" class="btn btn-secondary theme-btn me-12 order-2 order-md-1">Cancel</a>
                    <button type="submit" class="btn btn-primary theme-btn order-1 order-md-2">Create new employee</button>
                </div>
            </div>
        </form>
    </div>
</div>