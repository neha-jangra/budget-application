<div>
    <div wire:ignore.self class="modal fade theme-modal" id="add_user_project" tabindex="-1" aria-labelledby="add_donorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" class="theme-form" wire:submit.prevent="createProjectUser">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title text-primary-500 text-lg font-semibold" id="add_donorModalLabel">Add new employee</h1>
                        <button type="button" class="btn-close close-donor-modal" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body line-item-body">
                        <div class="row">
                            <div class="col-md-12 mb-3 display-none">
                                <div>
                                    <label class="theme-form-label">Type<span class="required">*</span></label>
                                    <select class="form-control @error('user_type') is-invalid @enderror user_type_line_item"
                                        wire:model="user_type" data-minimum-results-for-search="Infinity" id="user_type_line_item" data-placeholder="Choose type">
                                        @foreach($users as $key => $user)
                                        @if($key == 0)
                                        <option value="{{$user->id}}" selected>{{$user->title}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-12 mb-3">
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
                            <div class="col-md-6 mb-3">
                                <label class="theme-form-label">Email<span
                                        class="required">*</span></label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" wire:model="email"
                                    autocomplete="email" autofocus placeholder="example@mail.com">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="theme-form-label">Contact Number</label>
                                <div wire:ignore>
                                    <input type="tel"
                                        class="form-control phone_modal3"
                                        wire:model="phone_number" autocomplete="phone_number" autofocus
                                        placeholder="1231231234" wire:keydown.escape="updateOldPhoneNumber" wire:focusout="updateOldPhoneNumber">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="theme-form-label">Company Name</label>
                                <input type="text" class="form-control" wire:model="company_name" autocomplete="company" autofocus placeholder="Company Name">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="theme-form-label">Position</label>
                                <input id="position" type="text"
                                    class="form-control @error('position') is-invalid @enderror" wire:model="position"
                                    autocomplete="position" autofocus placeholder="Position">
                            </div>
                            <div class="col-md-12">
                                <label class="theme-form-label">Daily Rate<span class="required">*</span></label>
                                <div class="position-relative daily-rate-input">
                                    <div class="position-absolute currency-select2" wire:ignore>
                                        <select class="js-example-basic-single form-control @error('country_rate') is-invalid @enderror" wire:model="country_rate" data-minimum-results-for-search="Infinity">
                                            <option value="eur">EUR</option>
                                            <option value="usd">USD</option>
                                            <option value="gbp">GBP</option>
                                        </select>
                                    </div>
                                    <input id="employee_rate" type="text"
                                        class="form-control @error('rate') is-invalid @enderror" wire:model="rate"
                                        autocomplete="rate" autofocus placeholder="0">
                                    @error('rate')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary theme-btn close-donor-modal" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-primary theme-btn submit_line_item">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- start consultant-->
    <div wire:ignore.self class="modal fade theme-modal" id="add_consultant_project" tabindex="-1" aria-labelledby="add_donorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" class="theme-form" wire:submit.prevent="createProjectUser">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title text-primary-500 text-lg font-semibold" id="add_donorModalLabel">Add new consultant</h1>
                        <button type="button" class="btn-close close-donor-modal" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body line-item-body">
                        <div class="row">
                            <div class="col-md-12 mb-3 display-none">
                                <div>
                                    <label class="theme-form-label">Type<span class="required">*</span></label>
                                    <select class="form-control @error('user_type') is-invalid @enderror user_type_line_item1"
                                        wire:model="user_type" data-minimum-results-for-search="Infinity" id="user_type_line_item1" data-placeholder="Choose type">

                                        @foreach($users as $key2 => $user)
                                        @if($key2 == 2)
                                        <option value="{{$user->id}}">{{$user->title}}</option>
                                        @endif
                                        @endforeach
                                    </select>

                                </div>
                                @error('user_type')
                                <span class="invalid-feedback alert-error-dropdown" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="theme-form-label">First Name<span
                                        class="required">*</span></label>
                                <input id="firstname" type="text"
                                    class="form-control @error('first_name') is-invalid @enderror" wire:model="first_name"
                                    autocomplete="firstname" autofocus placeholder="First Name">
                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="theme-form-label">Last Name<span
                                        class="required">*</span></label>
                                <input id="lastname" type="text"
                                    class="form-control @error('last_name') is-invalid @enderror" wire:model="last_name"
                                    autocomplete="lastname" autofocus placeholder="Last Name">
                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="theme-form-label">Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" wire:model="email"
                                    autocomplete="email" autofocus placeholder="example@mail.com">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="theme-form-label">Contact Number</label>
                                <div wire:ignore>
                                    <input type="tel"
                                        class="form-control phone_modal3"
                                        wire:model="phone_number" autocomplete="phone_number" autofocus
                                        placeholder="1231231234" wire:keydown.escape="updateOldPhoneNumber" wire:focusout="updateOldPhoneNumber">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="theme-form-label">Company Name</label>
                                <input type="text" class="form-control" wire:model="company_name" autocomplete="company" autofocus placeholder="Company Name">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="theme-form-label">Position</label>
                                <input id="position" type="text"
                                    class="form-control @error('position') is-invalid @enderror" wire:model="position"
                                    autocomplete="position" autofocus placeholder="Position">
                            </div>
                            <div class="col-md-12">
                                <label class="theme-form-label">Daily Rate<span class="required">*</span></label>
                                <div class="position-relative daily-rate-input">
                                    <div class="position-absolute currency-select2" wire:ignore>
                                        <select class="js-example-basic-single form-control @error('country_rate') is-invalid @enderror" wire:model="country_rate" data-minimum-results-for-search="Infinity">
                                            <option value="eur">EUR</option>
                                            <option value="usd">USD</option>
                                            <option value="gbp">GBP</option>
                                        </select>
                                    </div>
                                    <input id="consultant_rate" type="text"
                                        class="form-control @error('rate') is-invalid @enderror" wire:model="rate"
                                        autocomplete="rate" autofocus placeholder="0">
                                    @error('rate')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary theme-btn close-donor-modal" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-primary theme-btn submit_line_item">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- end consultant-->


    <!-- start sub-grantee-->
    <div wire:ignore.self class="modal fade theme-modal" id="add_sub_grantee_project" tabindex="-1" aria-labelledby="add_donorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" class="theme-form" wire:submit.prevent="createProjectUser">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title text-primary-500 text-lg font-semibold" id="add_donorModalLabel">Add new Sub-grantee</h1>
                        <button type="button" class="btn-close close-donor-modal" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body line-item-body">
                        <div class="row">
                            <div class="col-md-12 mb-3 display-none">
                                <div>
                                    <label class="theme-form-label">Type<span class="required">*</span></label>
                                    <select class="form-control @error('user_type') is-invalid @enderror user_type_line_item2"
                                        wire:model="user_type" data-minimum-results-for-search="Infinity" id="user_type_line_item2" data-placeholder="Choose type">
                                        @foreach($users as $key3 => $user)
                                        @if($key3 == 1)
                                        <option value="{{$user->id}}">{{$user->title}}</option>
                                        @endif
                                        @endforeach
                                    </select>

                                </div>
                                @error('user_type')
                                <span class="invalid-feedback alert-error-dropdown" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="theme-form-label">First Name<span
                                        class="required">*</span></label>
                                <input id="firstname" type="text"
                                    class="form-control @error('first_name') is-invalid @enderror" wire:model="first_name"
                                    autocomplete="firstname" autofocus placeholder="First Name">
                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="theme-form-label">Last Name<span
                                        class="required">*</span></label>
                                <input id="lastname" type="text"
                                    class="form-control @error('last_name') is-invalid @enderror" wire:model="last_name"
                                    autocomplete="lastname" autofocus placeholder="Last Name">
                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="theme-form-label">Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" wire:model="email"
                                    autocomplete="email" autofocus placeholder="example@mail.com">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="theme-form-label">Contact Number</label>
                                <div wire:ignore>
                                    <input type="tel"
                                        class="form-control phone_modal3"
                                        wire:model="phone_number" autocomplete="phone_number" autofocus
                                        placeholder="1231231234" wire:keydown.escape="updateOldPhoneNumber" wire:focusout="updateOldPhoneNumber">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="theme-form-label">Company Name</label>
                                <input type="text" class="form-control" wire:model="company_name" autocomplete="company" autofocus placeholder="Company Name">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="theme-form-label">Position</label>
                                <input id="position" type="text"
                                    class="form-control @error('position') is-invalid @enderror" wire:model="position"
                                    autocomplete="position" autofocus placeholder="Position">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary theme-btn close-donor-modal" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-primary theme-btn submit_line_item">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end sub-grantee-->