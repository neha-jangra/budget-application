<div wire:ignore.self class="modal fade theme-modal delete-modal delete_lineItem" id="delete_lineItem_consultant" tabindex="-1" aria-labelledby="delete_lineItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="#" class="theme-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-primary-500 text-lg font-semibold" id="delete_lineItemLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center">
                        <img src="{{ asset('images/delete-modal-image.svg') }}" alt="bin" class="mb-4">
                        <h6 class="text-gray-800 text-xl font-semibold text-center mb-2">Are you sure to delete this consultant?</h6>
                        <p class="text-gray-500 text-sm font-regular text-center mb-0">Are you sure you want to delete this consultant?<br>This action cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center flex-wrap flex-md-nowrap gap-3 gap-md-0">
                    <button type="button" class="btn btn-secondary order-2 order-md-1" data-bs-dismiss="modal">Cancel</button>
                    <a wire:click.prevent="delete" class="btn btn-primary order-1 order-md-2">Delete</a>
                </div>
            </div>
        </form>
    </div>
</div>