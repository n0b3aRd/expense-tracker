<div wire:ignore.self class="modal modal-blur fade" id="expenseModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit="saveExpense">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control @error('expense_name') is-invalid @enderror" wire:model.blure="expense_name" />
                        @error('expense_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Category</label>
                            <select type="text" class="form-select @error('expense_category_id') is-invalid @enderror" wire:model.blure="expense_category_id">
                                <option value="">Select Category</option>
                                @foreach($expenses_categories as $expense_category)
                                    <option value="{{$expense_category->id}}">{{$expense_category->name}}</option>
                                @endforeach
                            </select>
                            @error('expense_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control @error('expense_amount') is-invalid @enderror" wire:model.blure="expense_amount" />
                            @error('expense_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Paid Date</label>
                            <input type="date" class="form-control @error('paid_at') is-invalid @enderror" placeholder="Select a date" wire:model.blure="paid_at">
                            @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Status</label>
                            <label class="row">
                                <span class="col">Already Paid</span>
                                <span class="col-auto">
                                    <label class="form-check form-check-single form-switch">
                                      <input class="form-check-input" type="checkbox" wire:model.blure="expense_status">
                                    </label>
                                  </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit"  class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>