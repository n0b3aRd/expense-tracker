<div>
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">List of</div>
                <h2 class="page-title">Income Categories</h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-primary d-none d-sm-inline-block" wire:click="create">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Add New
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.flash_message')
    <div class="d-flex mb-4">
        <div class="text-secondary">
            Show
            <div class="mx-2 d-inline-block">
                <select class="form-select" wire:model.live="table_entries_count">
                    <option value="10">10</option>
                    <option value="15">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            entries
        </div>
        <div class="ms-auto input-icon">
            <span class="input-icon-addon"> <!-- Download SVG icon from http://tabler-icons.io/i/search -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
            </span>
            <input type="text" wire:model.live.debounce.500ms="table_single_search" class="form-control" placeholder="Search…" aria-label="Search">
        </div>
    </div>
    <div class="card">
        <div class="">
            <table class="table table-vcenter card-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th class="w-7"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->name ?? '' }}</td>
                        <td>
                            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#editModal" wire:click="edit({{ $category->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                    <path d="M16 5l3 3"></path>
                                </svg>
                            </a>
                            <a href="#" class="text-danger ms-1" data-bs-toggle="modal" data-bs-target="#deleteModel" wire:click="setTarget({{ $category->id }})">
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
                    <tr>
                        <td colspan="2">
                            @include('common.no_result_found')
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
    @include('common.delete_model')
    @include('income_category.edit_modal')
    @include('income_category.create_modal')
</div>
@push('custom_scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
        @this.on('income-category-saved', (event) => {
            $('#createModal .btn-close').trigger('click');
        });
        @this.on('income-category-updated', (event) => {
            $('#editModal .btn-close').trigger('click');
        });
        });
    </script>
@endpush