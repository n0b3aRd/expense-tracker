<div wire:ignore.self class="modal modal-blur fade" id="incomeModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Income</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit="saveIncome">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control @error('income_name') is-invalid @enderror" wire:model.blure="income_name" />
                        @error('income_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Category</label>
                            <select type="text" class="form-select @error('income_category_id') is-invalid @enderror" wire:model.blure="income_category_id">
                                <option value="">Select Category</option>
                                @foreach($income_categories as $income_category)
                                    <option value="{{$income_category->id}}">{{$income_category->name}}</option>
                                @endforeach
                            </select>
                            @error('income_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control @error('income_amount') is-invalid @enderror" wire:model.blure="income_amount" />
                            @error('income_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Collected Date</label>
                            <input type="date" class="form-control @error('collected_at') is-invalid @enderror" placeholder="Select a date" wire:model.blure="collected_at">
                            @error('collected_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Status</label>
                            <label class="row">
                                <span class="col">Already Collected</span>
                                <span class="col-auto">
                                    <label class="form-check form-check-single form-switch">
                                      <input class="form-check-input" type="checkbox" wire:model.blure="income_status">
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