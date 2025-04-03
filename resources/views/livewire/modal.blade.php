<div wire:ignore.self class="modal fade theme-modal" id="add_donor" tabindex="-1" aria-labelledby="add_donorModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" class="theme-form" id="add_donor" wire:submit.prevent="createDonor">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-primary-500 text-lg font-semibold" id="add_donorModalLabel">Add new</h1>
                    <button type="button" class="btn-close" id="cancel-modal2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div wire:ignore>
                        <input type="hidden" id="user_type" wire:model="user_type" value="2" />
                    </div>
                    @error('user_type')
                    <span class="invalid-feedback alert-error-dropdown" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                    <div class="row gap-3">
                        <div class="col-md-12">
                            <div wire:ignore>
                                <input type="hidden" id="user_type" wire:model="user_type" value="2" />
                            </div>
                            @error('user_type')
                            <span class="invalid-feedback alert-error-dropdown" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="theme-form-label">Organization/Company</label>
                            <input id="company" type="text" class="form-control"
                                wire:model="company" autocomplete="company" autofocus placeholder="Company">
                        </div>

                        <div class="col-md-12">
                            <div wire:ignore>
                                <label class="theme-form-label">Select donor<span
                                        class="required">*</span></label>
                                <select
                                    class="js-example-basic-single form-control @error('donor_name') is-invalid @enderror donor_select2"
                                    wire:model="donor_name" data-placeholder="Select donor">
                                    <option value=""></option>
                                    @foreach ($exactDonors as $donor)
                                    <option value="{{ $donor->account_id }}">{{ $donor->account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('donor_name')
                            <span class="invalid-feedback alert-error-dropdown" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <div class="row gap-3 gap-md-0">
                                <div class="col-md-6">
                                    <label class="theme-form-label">Email<span
                                            class="required">*</span></label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" wire:model="email"
                                        autocomplete="email" autofocus placeholder="olivia@testmail.com">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="theme-form-label">Donor Contact Number</label>
                                    <div wire:ignore>
                                        <input type="tel"
                                            class="form-control phone_modal2"
                                            wire:model="donor_contact" autocomplete="donor_contact" autofocus
                                            placeholder="(201) 555-0123" wire:keydown.escape="updateOldPhoneNumber" wire:focusout="updateOldPhoneNumber">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-12">
                            <label class="theme-form-label">Gender</label>
                            <div class="row gap-1 gap-md-0">
                                <div class="col-md-4">
                                    <label class="radio-btn-container" for="flexRadioDefault1" >
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="male"
                                                wire:model="gender"   id="flexRadioDefault1" >
                                            <label class="form-check-label cursor-pointer" for="flexRadioDefault1">
                                                Male
                                            </label>
                                        </div>
                                    </label>
                                </div>
                               
                                <div class="col-md-4">
                                    <label class="radio-btn-container" for="flexRadioDefault2" >
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="female"
                                                wire:model="gender"   id="flexRadioDefault2" >
                                            <label class="form-check-label cursor-pointer" for="flexRadioDefault2">
                                                Female
                                            </label>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="radio-btn-container" for="flexRadioDefault3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="other"
                                                wire:model="gender"   id="flexRadioDefault3" >
                                            <label class="form-check-label cursor-pointer" for="flexRadioDefault3">
                                                Other
                                            </label>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="col-md-12">
                            <label class="theme-form-label">Address</label>
                            <input id="address" type="text" class="form-control"
                                wire:model="address" autocomplete="address" autofocus placeholder="Address">
                        </div>
                        <div class="col-md-12">
                            <div class="row gap-3 gap-md-0">
                                <div class="col-md-6">
                                    <label class="theme-form-label">City</label>
                                    <input id="city" type="text"
                                        class="form-control" wire:model="city"
                                        autocomplete="city" autofocus placeholder="City">
                                </div>
                                <div class="col-md-6">
                                    <label class="theme-form-label">State</label>
                                    <input id="state" type="text"
                                        class="form-control" wire:model="state"
                                        autocomplete="state" autofocus placeholder="State">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row gap-3 gap-md-0">
                                <div class="col-md-6">
                                    <label class="theme-form-label">Pin Code</label>
                                    <input id="pin_code" type="text"
                                        class="form-control"
                                        wire:model="pin_code" autocomplete="pin_code" autofocus placeholder="Pin Code">
                                </div>
                                <div class="col-md-6">
                                    <div wire:ignore>
                                        <label class="theme-form-label">Country</label>
                                        <select
                                            class="select_country_modal form-control w-100 donor_select2"
                                            wire:model="country_code"
                                            id="country" data-placeholder="Select Country">
                                            <option value=""></option>
                                            @foreach($countries as $value)
                                            <option value="{{$value->code}}">{{$value->country_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary theme-btn" data-bs-dismiss="modal" aria-label="Close" id="cancel-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary theme-btn">Save donor</button>
                </div>
            </div>
        </form>
    </div>
</div>