<div>
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">List of</div>
                <h2 class="page-title">Months ({{$current_year}})</h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <span class="d-none d-sm-inline">
                    <a href="{{ url('months/'.$current_month_id) }}" class="btn">
                      Current Month
                    </a>
                    </span>
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
    <div class="col-12">
        <div class="card border-0 bg-transparent">
            <div class="card-body px-0">
                <ul class="pagination">
                    <li class="page-item page-prev">
                        <a class="page-link" wire:click="previousYear" tabindex="-1" aria-disabled="true">
                            <div class="page-item-subtitle">{{ $previous_year }}</div>
                            <div class="page-item-title">PREVIOUS YEAR</div>
                        </a>
                    </li>
                    <li class="page-item page-next {{ $current_year < $next_year ? 'disabled' : '' }}">
                        <a class="page-link" wire:click="nextYear">
                            <div class="page-item-subtitle">{{ $next_year }}</div>
                            <div class="page-item-title">NEXT YEAR</div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row row-cards">
        @forelse($months as $month)
        <div class="col-md-6 col-xl-3">
            <a class="card card-link" href="{{ url('months/'.$month->id) }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-auto">
                            <span class="avatar rounded bg-primary text-white">{{ str_pad((integer)$month->month->format('m'), 2, 0, STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">{{ $month->name }}</div>
                            <span class="badge bg-green"></span>
                            <span class="text-secondary">Rs. {{ number_format($month->total_income, 2) }}</span>
                        </div>
                        <div class="col text-end">
                            <div class="font-weight-medium">{{ $month->total_income == 0 ? 0 : round(($month->total_expense / $month->total_income) * 100, 2) }}%</div>
                            <span class="badge bg-red"></span>
                            <span class="text-secondary">Rs. {{ number_format($month->total_expense, 2) }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
            <div class="col-12">
                @include('common.no_result_found')
            </div>
        @endforelse
    </div>
    @include('month.create_modal')
</div>
@push('custom_scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
        @this.on('month-saved', (event) => {
            $('#createModal .btn-close').trigger('click');
        });
        });
    </script>
@endpush