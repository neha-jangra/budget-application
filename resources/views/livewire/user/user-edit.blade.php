<div>
    <div class="page-header">
        <a href="{{route('user.index')}}" class="theme-link mb-3">
            <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.875 10H3.125" stroke="#667085" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.75 4.375L3.125 10L8.75 15.625" stroke="#667085" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Back to all users</span>
        </a>
        <h6 class="text-gray-800 h6 font-medium mb-4">Edit User</h6>
    </div>
    <div class="content withSideSpacing">
        <form class="theme-form-card theme-form" method="post" wire:submit.prevent="edit">
            <div class="form-field-container">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">First Name<span class="required">*</span></label>
                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"  wire:model="first_name" placeholder="First Name" autocomplete="first_name" autofocus>
                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Last Name<span class="required">*</span></label>
                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" wire:model="last_name" placeholder="Last Name" autocomplete="last_name" autofocus>
                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Email<span class="required">*</span></label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" placeholder="olivia@testmail.com" autocomplete="email" {{ ($user->id == 1) ? 'disabled' : ''}} autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="theme-form-label">Role<span class="required">*</span></label>
                        <div wire:ignore>
                            <select id="role_type" wire:model="role" type="text" class="js-example-basic-single form-control @error('role') is-invalid @enderror user-module-role-input"  {{ ($user->id == 1) ? 'disabled' : ''}} data-minimum-results-for-search="Infinity">
                                <option value="">Choose Role</option>
                                @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')
                        <span class="invalid-feedback alert-error-dropdown" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions d-flex justify-content-md-between flex-wrap flex-md-nowrap gap-2 gap-md-0">
                <div class="d-flex algin-items-center flex-wrap flex-md-nowrap gap-3 gap-md-0 order-1 order-md-2 ms-auto">
                    <a href="{{route('user.index')}}" class="btn btn-secondary theme-btn me-12 order-2 order-md-1">Cancel</a>
                    <button type="submit" class="btn btn-primary theme-btn order-1 order-md-2">Save changes</button>
                </div>
                <a wire:click.prevent="confirmDelete({{$user_id}})" data-bs-toggle="modal" data-bs-target="#delete_user" class="btn btn-secondary theme-btn btn-delete order-2 order-md-1">Delete</a>
            </div>
        </form>
    </div>
    @include('components.users.delete')
</div>