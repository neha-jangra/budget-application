<div>
    <form wire:submit.prevent="save" class="theme-form-card theme-form">
        <div class="form-field-container">
            <div class="row">
                <div class="col-md-12 mb-32">
                    <h4 class="text-gray-500 text-sm font-semibold mb-2">Photo</h4>
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        @if ($photo)
                            <img class="rounded-circle img-fluid imagePreview" src="{{ $photo->temporaryUrl() }}"
                                alt="Profile Photo" height="80" width="80"
                                style="height: 80px;width: 80px;object-fit: cover;">
                        @elseif(!$photoDeleted && $user->userprofile->photo)
                            <img class="rounded-circle img-fluid imagePreview" src="{{ asset('storage/' . $user->userprofile->photo) }}"
                                alt="Profile Photo" height="80" width="80"
                                style="height: 80px;width: 80px;object-fit: cover;">
                        @else
                            <img class="rounded-circle img-fluid imagePreview" src="/images/user-default-image.jpeg"
                                alt="Default Photo" height="80" width="80"
                                style="height: 80px;width: 80px;object-fit: cover;">
                        @endif
                        <div class="d-flex align-items-center gap-12">
                            <label class="btn btn-secondary theme-btn">
                                Change photo
                                <input type="file" class="d-none" wire:model="photo" accept="image/*">
                            </label>
                           @if (!$photoDeleted && ($user->userprofile->photo || $photo))
                                <button type="button" class="images_uploader delete-image-btn deleteButton" wire:click="deletePhoto">
                                    <img src="/images/icons/delete-bin.svg" alt="delete" data-bs-tooltip="toggle" title="Delete">
                                </button>
                            @endif

                        </div>
                    </div>
                    @error('photo') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-20">
                    <label class="theme-form-label">First Name<span class="required">*</span></label>
                    <input wire:model="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name">
                    @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-20">
                    <label class="theme-form-label">Last Name<span class="required">*</span></label>
                    <input wire:model="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last Name">
                    @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-32">
                    <label class="theme-form-label">Email<span class="required">*</span></label>
                    <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary theme-btn mb-4">
                Save changes
            </button>
        </div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </form>
</div>
