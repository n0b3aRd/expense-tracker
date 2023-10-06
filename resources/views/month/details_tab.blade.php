<div class="row">
    <div class="col-12 col-md-6 col-lg">
        <div class="col-12 d-flex align-items-center mb-1">
            <h2 class="mb-3">Expenses</h2>
            <button class="btn btn-outline-primary ms-auto" data-bs-toggle="modal" data-bs-target="#expenseModal" wire:click="createExpense">Add New</button>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-center">Amount</th>
                            <th class="w-7"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>
                                    <div class="d-flex py-1 align-items-center">
                                        <span class="avatar me-2">{{ $expense->paid_at ? str_pad($expense->paid_at->format('d'), 2, '0', STR_PAD_LEFT) : 00 }}</span>
                                        <div class="flex-fill">
                                            <div class="font-weight-medium">{{ $expense->name }}</div>
                                            <div class="text-secondary">
                                                <span class="badge {{ $expense->status == 'Pending' ? 'bg-red' : 'bg-green' }}"></span>
                                                <span class="text-reset">{{ $expense->category->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="text-secondary text-nowrap">Rs. {{ number_format($expense->amount, 2) }}</div>
                                </td>
                                <td>
                                    <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#expenseModal" wire:click="updateExpense({{$expense->id}})">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                            <path d="M16 5l3 3"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-danger ms-1" data-bs-toggle="modal" data-bs-target="#deleteModel" wire:click="setTarget({{ $expense->id }}, 'expense')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 7l16 0"></path>
                                            <path d="M10 11l0 6"></path>
                                            <path d="M14 11l0 6"></path>
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center">
                                <td colspan="3" class="text-secondary">No data found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg mt-4 mt-md-0">
        <div class="col-12 d-flex align-items-center mb-1">
            <h2 class="mb-3">Income</h2>
            <button data-bs-toggle="modal" data-bs-target="#incomeModal" wire:click="createIncome" class="btn btn-outline-primary ms-auto">Add New</button>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-center">Amount</th>
                        <th class="w-7"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($incomes as $income)
                        <tr>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <span class="avatar me-2">{{ $income->collected_at ? str_pad($income->collected_at->format('d'), 2, '0', STR_PAD_LEFT) : 00 }}</span>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium">{{ $income->name }}</div>
                                        <div class="text-secondary">
                                            <span class="badge {{ $income->status == 'Pending' ? 'bg-red' : 'bg-green' }}"></span>
                                            <span class="text-reset">{{ $income->category->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="text-secondary text-nowrap me-3">Rs. {{ number_format($income->amount, 2) }}</div>
                            </td>
                            <td>
                                <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#incomeModal" wire:click="updateIncome({{$income->id}})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                        <path d="M16 5l3 3"></path>
                                    </svg>
                                </a>
                                <a href="#" class="text-danger ms-1" data-bs-toggle="modal" data-bs-target="#deleteModel" wire:click="setTarget({{ $income->id }}, 'income')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 7l16 0"></path>
                                        <path d="M10 11l0 6"></path>
                                        <path d="M14 11l0 6"></path>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="3" class="text-secondary">No data found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>