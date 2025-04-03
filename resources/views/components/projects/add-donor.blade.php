<div class="modal fade theme-modal" id="add_donor" tabindex="-1" aria-labelledby="add_donorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="" class="theme-form">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-primary-500 text-lg font-semibold" id="add_donorModalLabel">Add new</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="theme-form-label">Type<span class="required">*</span></label>
                        <select class="js-example-basic-single form-control w-100" name="type" data-minimum-results-for-search="Infinity" required>
                            <option value="Donor">Donor</option>
                            <option value="Employee">Employee</option>
                            <option value="Sub-grantee">Sub-grantee</option>
                            <option value="Consultant">Consultant</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="theme-form-label">Organization/Company<span class="required">*</span></label>
                        <input id="company" type="text" class="form-control @error('company') is-invalid @enderror" name="company" value="" autocomplete="company" autofocus>
                            @error('company')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="theme-form-label">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="" autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="theme-form-label">Donor Contact Number</label>
                                <input type="tel" class="form-control phone @error('donor_contact') is-invalid @enderror" name="donor_contact" value="" autocomplete="donor_contact" autofocus>
                                    @error('donor_contact')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="theme-form-label">Gender</label>
                        <div class="row gap-1 gap-md-0">
                            <div class="col-md-4">
                                <label class="radio-btn-container" for="flexRadioDefault1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                                        <label class="form-check-label cursor-pointer" for="flexRadioDefault1">
                                            Male
                                        </label>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="radio-btn-container" for="flexRadioDefault2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                        <label class="form-check-label cursor-pointer" for="flexRadioDefault2">
                                        Female
                                        </label>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="radio-btn-container" for="flexRadioDefault3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3" >
                                        <label class="form-check-label cursor-pointer" for="flexRadioDefault3">
                                        Other
                                        </label>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="theme-form-label">Address</label>
                        <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="" autocomplete="address" autofocus>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="theme-form-label">City</label>
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="" autocomplete="city" autofocus>
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="theme-form-label">State</label>
                                <input id="state" type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="" autocomplete="state" autofocus>
                                    @error('state')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="theme-form-label">Pin Code</label>
                                <input id="pin_code" type="number" class="form-control @error('pin_code') is-invalid @enderror" name="pin_code" value="" autocomplete="pin_code" autofocus>
                                    @error('pin_code')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="theme-form-label">Country</label>
                                <input id="country" type="text" class="form-control @error('country') is-invalid @enderror" name="country" value="" autocomplete="country" autofocus>
                                    @error('country')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary theme-btn" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary theme-btn">Save donor</button>
            </div>
        </div>
    </form>
  </div>
</div>