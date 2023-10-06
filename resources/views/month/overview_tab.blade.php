<div>
    <div class="row row-cards">
        <div class="col-md-6 col-xl-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                        <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-repeat" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3"></path>
                            <path d="M16 3v4"></path>
                            <path d="M8 3v4"></path>
                            <path d="M4 11h12"></path>
                            <path d="M20 14l2 2h-3"></path>
                            <path d="M20 18l2 -2"></path>
                            <path d="M19 16a3 3 0 1 0 2 5.236"></path>
                          </svg>
                        </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ number_format($month->pre_balance, 2) }}
                            </div>
                            <div class="text-secondary">
                                Previous Balance
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                        <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-big-down-lines" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M15 12h3.586a1 1 0 0 1 .707 1.707l-6.586 6.586a1 1 0 0 1 -1.414 0l-6.586 -6.586a1 1 0 0 1 .707 -1.707h3.586v-3h6v3z"></path>
                            <path d="M15 3h-6"></path>
                            <path d="M15 6h-6"></path>
                          </svg>
                        </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ number_format($month->total_income, 2) }}
                            </div>
                            <div class="text-secondary">
                                Total Income
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                        <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-big-up-lines" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M9 12h-3.586a1 1 0 0 1 -.707 -1.707l6.586 -6.586a1 1 0 0 1 1.414 0l6.586 6.586a1 1 0 0 1 -.707 1.707h-3.586v3h-6v-3z"></path>
                            <path d="M9 21h6"></path>
                            <path d="M9 18h6"></path>
                          </svg>
                        </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ number_format($month->total_expense, 2) }}
                            </div>
                            <div class="text-secondary">
                                Total Expense
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                        <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-dollar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M13 21h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3"></path>
                            <path d="M16 3v4"></path>
                            <path d="M8 3v4"></path>
                            <path d="M4 11h12.5"></path>
                            <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                            <path d="M19 21v1m0 -8v1"></path>
                          </svg>
                        </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ number_format(($month->pre_balance + $month->total_income) - $month->total_expense, 2) }}
                            </div>
                            <div class="text-secondary">
                                Month Balance
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Expense Amounts</h3>
                </div>
                <div class="card-body">
                    <div id="budget_chart"></div>
                </div>

            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Budget Allocation</h3>
                </div>
                <table class="table card-table table-vcenter">
                    <thead>
                    <tr>
                        <th>Category</th>
                        <th colspan="2">Budget</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($expense_summary as $category)
                    <tr>
                        <td>{{ $category['name'] }}</td>
                        <td>{{ $category['budget_allocation'] }}</td>
                        <td class="w-50">
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-primary" style="width: {{$category['remaining']}}%"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <th colspan="3">No data found.</th>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('custom_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var data = @json($expense_chart_data);
            var options = {
                series: [
                    {
                        name: 'Actual',
                        data: data
                    }
                ],
                chart: {
                    height: 350,
                    type: 'bar'
                },
                plotOptions: {
                    bar: {
                        columnWidth: '60%'
                    }
                },
                colors: ['#0054a6'],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: true,
                    showForSingleSeries: true,
                    customLegendItems: ['Actual', 'Allocation'],
                    markers: {
                        fillColors: ['#0054a6', '#2fb344']
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#budget_chart"), options);
            chart.render();
        });
    </script>
@endpush