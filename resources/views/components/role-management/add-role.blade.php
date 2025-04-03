<div wire:ignore.self class="modal fade theme-modal add-role" id="add_role" tabindex="-1" aria-labelledby="add_roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" class="theme-form" wire:submit.prevent="roleStore">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-primary-500 text-lg font-semibold" id="add_roleModalLabel">Add new role</h1>
                    <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="theme-form-label">Role Name<span class="required">*</span></label>
                            <input type="text" class="form-control @error('role_name') is-invalid @enderror" wire:model="role_name" autocomplete="role_name" autofocus>
                            @error('role_name')
                            <span class="invalid-feedback alert-error-dropdown" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary theme-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary theme-btn">Save change</button>
                </div>
            </div>
        </form>
    </div>
</div>