<div>
    <form wire:submit.prevent="save" class="theme-form-card theme-form needs-validation" novalidate>
        @csrf
        <div class="form-field-container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-gray-800 text-xl font-semibold mb-4">Change your password</h4>
                </div>
                <div class="col-md-12 mb-20">
                    <label for="current_password" class="text-gray-50 text-sm font-medium p-0 mb-6">Current Password<span class="required">*</span></label>
                    <div class="input-addon">
                        <input wire:model="current_password" type="password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required placeholder="Current password">
                        @error('current_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <div class="add-on" onclick="togglePasswordVisibilitySetting('current_password')" style="cursor: pointer;">
                            <img id="current_password_eyeIcon" src="/images/icons/eye-slash.svg" alt="eye-slash">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-20">
                    <label for="new_password" class="text-gray-50 text-sm font-medium p-0 mb-6">New Password<span class="required">*</span></label>
                    <div class="input-addon">
                        <input wire:model="new_password" type="password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" required placeholder="New password">
                        @error('new_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <div class="add-on" onclick="togglePasswordVisibilitySetting('new_password')" style="cursor: pointer;">
                            <img id="new_password_eyeIcon" src="/images/icons/eye-slash.svg" alt="eye-slash">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-32">
                    <label for="confirm_password" class="text-gray-50 text-sm font-medium p-0 mb-6">Confirm New Password<span class="required">*</span></label>
                    <div class="input-addon">
                        <input wire:model="confirm_password" type="password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" required placeholder="Confirm new password">
                        @error('confirm_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <div class="add-on" onclick="togglePasswordVisibilitySetting('confirm_password')" style="cursor: pointer;">
                            <img id="confirm_password_eyeIcon" src="/images/icons/eye-slash.svg" alt="eye-slash">
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary theme-btn mb-4">
                Save changes
            </button>
        </div>
    </form>
</div>
