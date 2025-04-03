<div>
    <div class="page-header">
        <a href="{{ route('donor.index') }}" class="theme-link mb-3">
            <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M16.875 10H3.125" stroke="#667085" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M8.75 4.375L3.125 10L8.75 15.625" stroke="#667085" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span>Back to all donors</span>
        </a>
        <h6 class="text-gray-800 h6 font-medium mb-4">Add Donor</h6>
    </div>
    <div class="content withSideSpacing">
        <form class="theme-form-card theme-form" method="post" wire:submit.prevent="store">
            <div class="form-field-container">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Select donor<span class="required">*</span></label>
                        <div wire:ignore>
                            <select
                                class="js-example-basic-single form-control @error('name') is-invalid @enderror donor_select2"
                                wire:model="name" data-placeholder="Select donor">
                                <option value=""></option>
                                @foreach ($exactDonors as $donor)
                                    <option value="{{ $donor->account_id }}">{{ $donor->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('name')
                        <span class="invalid-feedback alert-error-dropdown" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Organization/Company</label>
                        <input id="organization" type="text"
                            class="form-control @error('company_name') is-invalid @enderror" wire:model="company_name"
                            placeholder="Company Name" autocomplete="organization" autofocus>
                        @error('company_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Donor Contact Number</label>
                        <div wire:ignore>
                            <input id="phone" type="tel" class="form-control donor_create_phone w-100"
                                wire:model="phone_number" placeholder="1231231234" autocomplete="donor_phone_number"
                                autofocus wire:keydown.escape="updateOldPhoneNumber" wire:blur="updateOldPhoneNumber">
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Email<span class="required">*</span></label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            wire:model="email" placeholder="olivia@testmail.com" autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-xl-12 mb-3">
                        <label class="theme-form-label">Address</label>
                        <input id="address" type="text"
                            class="form-control @error('address') is-invalid @enderror" wire:model="address"
                            placeholder="Address" autocomplete="address" autofocus>
                        @error('address')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">City</label>
                        <input id="city" type="text" class="form-control @error('city') is-invalid @enderror"
                            wire:model="city" placeholder="City" autocomplete="city" autofocus>
                        @error('city')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">State</label>
                        <input id="state" type="text"
                            class="form-control @error('state') is-invalid @enderror" wire:model="state"
                            placeholder="State" autocomplete="state" autofocus>
                        @error('state')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Project Code</label>
                        <input id="project_code" type="text"
                            class="form-control @error('project_code') is-invalid @enderror" wire:model="project_code"
                            placeholder="Project Code" autocomplete="project_code" autofocus>
                        @error('project_code')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Country</label>
                        <div wire:ignore>
                            <select
                                class="js-example-basic-single form-control @error('country_code') is-invalid @enderror donor_select2"
                                wire:model="country_code" data-placeholder="Select Country">
                                <option value=""></option>
                                @foreach ($countries as $value)
                                <option value="{{ $value->code }}">{{ $value->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('country_code')
                        <span class="invalid-feedback alert-error-dropdown" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions d-flex justify-content-md-between flex-wrap flex-md-nowrap gap-2 gap-md-0">
                <div
                    class="d-flex algin-items-center flex-wrap flex-md-nowrap gap-3 gap-md-0 order-1 order-md-2 ms-auto">
                    <a href="{{ route('donor.index') }}"
                        class="btn btn-secondary theme-btn me-12 order-2 order-md-1">Cancel</a>
                    <button type="submit" class="btn btn-primary theme-btn order-1 order-md-2">Create new
                        donor</button>
                </div>
            </div>
        </form>
    </div>
</div>
