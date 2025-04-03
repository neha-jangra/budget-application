<div>
  @if($isOpen)
    <div wire:ignore.self class="modal fade theme-modal add-other-direct-expenses" id="add_other_direct_expenses" tabindex="-1" aria-labelledby="add_other_direct_expenses" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" class="theme-form other-direct-expenses-form" wire:submit.prevent="addOtherIndirectExpense">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title text-primary-500 text-lg font-semibold" id="add_other_direct_expensesLabel">Add new other direct expense</h1>
                        <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-12">
                                <label class="theme-form-label">Other Direct Expense Name<span class="required">*</span></label>
                                <input type="text" class="form-control @error('direct_expenses_name') is-invalid @enderror" wire:model="direct_expenses_name" autocomplete="direct_expenses_name" autofocus>
                                @error('direct_expenses_name')
                                <span class="invalid-feedback alert-error-dropdown" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check my-3">
                                    <input class="form-check-input" type="checkbox" name="flexRadioDefault" id="overhead_expense" checked>
                                    <label class="form-check-label" for="overhead_expense">
                                        This is an overhead expense
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary theme-btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary theme-btn">Create expense</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
  @endif
</div>
