<div>
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">{{ $month->month->format('Y') }}</div>
                <h2 class="page-title">{{ $month->name }}</h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <span class="d-none d-sm-inline">
                    <a href="{{ route('months.index') }}" class="btn">
                      All Months
                    </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.flash_message')
    <ul class="nav nav-bordered mb-4">
        <li class="nav-item" role="presentation">
            <a href="#tab-details" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">Details</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="#tab-overview" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Overview</a>
        </li>
    </ul>
    <div class="tab-content">
        <!-- Content of card #1 -->
        <div id="tab-details" class="tab-pane active" role="tabpanel">
            @include('month.details_tab')
        </div>
        <!-- Content of card #2 -->
        <div id="tab-overview" class="tab-pane" role="tabpanel">
            @include('month.overview_tab')
        </div>
    </div>
    @include('month.expense_model')
    @include('month.income_model')
    @include('common.delete_model')
</div>
@push('custom_scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
    @this.on('expense-saved', (event) => {
        $('#expenseModal .btn-close').trigger('click');
    });
    @this.on('income-saved', (event) => {
        $('#incomeModal .btn-close').trigger('click');
    });
    });
</script>
@endpush