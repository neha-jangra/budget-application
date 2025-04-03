<div>
    <div class="page-header">
        <a href="/line-item?active_tab=tab2" class="theme-link mb-3">
            <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.875 10H3.125" stroke="#667085" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.75 4.375L3.125 10L8.75 15.625" stroke="#667085" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Back to all sub-grantees</span>
        </a>
        <h6 class="text-gray-800 h6 font-medium mb-4">Add New Sub-grantee</h6>
    </div>
    <div class="content withSideSpacing">
        <form class="theme-form-card theme-form" method="post" wire:submit.prevent="store">
            <div class="form-field-container">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">First Name<span class="required">*</span></label>
                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name" wire:model="first_name" autocomplete="off" autofocus @if($userExists) disabled @endif>
                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Last Name<span class="required">*</span></label>
                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last Name" wire:model="last_name" autocomplete="off" autofocus @if($userExists) disabled @endif>
                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"  placeholder="olivia@testmail.com" wire:model="email" autocomplete="off" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
              
                        @if ($userExists)
                            <span class="valid-feedback" style="display:{{ $userExists ? 'block' : 'none' }};">
                                This user already exists. Upon saving, they will also be designated as sub-grantee.
                            </span>
                        @endif
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Phone Number</label>
                        <div wire:ignore>
                            <input id="phone" type="tel" class="form-control donor_create_phone w-100" placeholder="1231231234" wire:model="phone_number" autocomplete="off" autofocus wire:keydown.escape="updateOldPhoneNumber" wire:blur="updateOldPhoneNumber">
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label class="theme-form-label">Company Name</label>
                        <input id="company" type="text" class="form-control" placeholder="Company Name" wire:model="company" autocomplete="off" autofocus>
                    </div>
                    
                </div>
            </div>

            <div class="form-actions d-flex justify-content-md-between flex-wrap flex-md-nowrap gap-2 gap-md-0">
                <div class="d-flex algin-items-center flex-wrap flex-md-nowrap gap-3 gap-md-0 order-1 order-md-2 ms-auto">
                    <a href="/line-item?active_tab=tab2" class="btn btn-secondary theme-btn me-12 order-2 order-md-1">Cancel</a>
                    <button type="submit" class="btn btn-primary theme-btn order-1 order-md-2">Create new sub-grantee</button>
                </div>
            </div>
        </form>
    </div>
</div>