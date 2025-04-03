@if ($loader)
<livewire:loader />
@endif
<div wire:ignore.self class="modal fade theme-modal subproject-modal" id="sub-project-modal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" wire:submit.prevent="createSubproject" class="theme-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary-500 text-lg font-semibold" id="exampleModalLabel">Add new sub-project</h5>
                    <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    @if ($subProjects)
                    <div class="alert-msg px-3 py-2">
                        <div class="d-flex gap-2">
                            <div>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z"
                                        stroke="#F79009" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M11.25 11.25H12V16.5H12.75" stroke="#F79009" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M11.8125 9C12.4338 9 12.9375 8.49632 12.9375 7.875C12.9375 7.25368 12.4338 6.75 11.8125 6.75C11.1912 6.75 10.6875 7.25368 10.6875 7.875C10.6875 8.49632 11.1912 9 11.8125 9Z"
                                        fill="#F79009" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-0">You've already entered data. Before you may start a new
                                    sub-project,
                                    your
                                    previous input
                                    will be converted into a sub-project. Give your previous input a name below.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="">
                        <div wire:ignore.self>
                            <label
                                class="text-gray-50 text-sm font-medium p-0 mb-6">{{ $subProjects ? 'Previous Input Project Name' : 'Select project' }}<span
                                    class="required">*</span></label>
                            <select class="js-example-basic-single form-control"
                                wire:model="sub_project_id" data-placeholder="Select sub-project">
                                <option value="" selected>Select project</option>
                                @foreach($exactSubProjects as $subProject)
                                <option value="{{$subProject->exact_id}}">{{$subProject->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" wire:model="year">
                        @error('sub_project_id')
                        <span class="alert-error-dropdown" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                        <div class="text-gray-50 text-sm font-medium p-0 mt-3">
                            Note: Creating a sub-project may take up to 30 seconds.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary theme-btn" data-bs-dismiss="modal">Cancel</a>
                    <button type="submit"
                        class="btn btn-primary theme-btn">{{ $subProjects ? 'Convert to sub-project' : 'Create sub-project' }}</button>
                </div>
            </div>
        </form>
    </div>
</div>