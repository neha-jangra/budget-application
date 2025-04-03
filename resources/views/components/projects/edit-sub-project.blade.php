<div wire:ignore.self class="modal fade theme-modal subproject-modal edit-sub-project" id="edit_sub_project_modal" tabindex="-1" aria-labelledby="edit_sub_project_ModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" class="theme-form" wire:submit.prevent="editSubProject">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-primary-500 text-lg font-semibold" id="edit_sub_project_ModalLabel">Edit sub-project</h1>
                    <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div>
                                <label class="theme-form-label">Project Name<span class="required">*</span></label><select class="js-example-basic-single subproject-select js-project-code form-control @error('sub_project_name') is-invalid @enderror" id="sub_project_name_input" wire:ignore.self
                                data-placeholder="Select project code">
                                <option value="">Select project code</option>
                                <option value="1">AK-1000</option>
                                <option value="2">AL-1000</option>
                                <option value="3">BH-1000</option>
                                <option value="4">CO-1001</option>
                                <option value="5">CO-1013</option>
                                <option value="6">CO-2007</option>
                                <option value="7">EF-3000</option>
                                <option value="8">EG-1000</option>
                                </select>
                                <!-- <input type="text" class="form-control @error('sub_project_name') is-invalid @enderror" wire:model="sub_project_model_name" placeholder="Project Name" autocomplete="subproject" autofocus id="sub_project_name_input" wire:ignore.self>
                                <input type="hidden" class="form-control" wire:model="sub_project_model_id" id="sub_project_id_input"> -->
                                @error('sub_project_model_name')
                                <span class="invalid-feedback alert-error-dropdown" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
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