@extends('_layouts.main')

@push('page')
    Dashboard
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('dashboard') }}
@endpush

@push('toolbar')
    <div class="col-auto align-self-center">
        <div class="row">
            <div class="input-group">
                <span type="button" class="btn btn-outline-primary rounded" style="margin-right: 1rem !important;"
                      id="db_date_filter"></span>

                {{--                <a href="#" class="btn btn-sm btn-outline-primary rounded" title="Download report">--}}
                {{--                    <i data-feather="download" class="align-self-center icon-xs"></i>--}}
                {{--                </a>--}}
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Revenue Status</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div id="Revenue_Status" class="apex-charts"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col text-center">
                                    <span class="h4">{{ $open_paygates }}</span>
                                    <h6 class="text-uppercase text-muted mt-2 m-0">Open Paygates</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col text-center">
                                    <span class="h4">{{ $live_stores }}</span>
                                    <h6 class="text-uppercase text-muted mt-2 m-0">Live Stores</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col text-center">
                                    <span class="h4">{{ $success_orders }}</span>
                                    <h6 class="text-uppercase text-muted mt-2 m-0">Success Orders</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col text-center">
                                    <span class="h4">2.8%</span>
                                    <h6 class="text-uppercase text-muted mt-2 m-0">Dispute Rate</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <i data-feather="calendar" class="align-self-center icon-xs me-1"></i>
                    <span class="fw-bold">Last analyzed at: </span>
                    <span class="text-info">{{ date('D d/m/Y') }}</span>
                </div>
            </div>
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="media">
                                {{--                                <img src="{{ Vite::asset('resources/assets/images/money-beg.png') }}" alt="" class="align-self-center" height="40">--}}
                                <div class="media-body align-self-center ms-3">
                                    <h6 class="m-0 font-20">{{ number_format($total_revenue, 2) }} $</h6>
                                    <p class="text-muted mb-0">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto align-self-center">
                            <p class="mb-0"><span class="text-success"><i class="mdi mdi-trending-up"></i>4.8%</span>
                                increase</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="apexchart-wrapper">
                            <div id="area_chart" class="chart-gutters"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Dispute Reports</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div id="dispute_chart" class="apex-charts"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Dispute Reports</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table mb-0">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 2">
                            <tr>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">Open</th>
                                <th class="border-top-0">Resolved</th>
                                <th class="border-top-0">Failed</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dispute_reports as $date => $report)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                    <td>{{ $report['open'] }}</td>
                                    <td class="text-success">{{ $report['resolved'] }}</td>
                                    <td class="text-danger">{{ $report['failed'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Paygate Reports</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="border-top-0">Created At</th>
                                <th class="border-top-0">Paygate ID</th>
                                <th class="border-top-0">Type</th>
                                <th class="border-top-0">Revenue</th>
                                <th class="border-top-0">Dispute Rate</th>
                                <th class="border-top-0">Limit</th>
                                <th class="border-top-0">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>01/07/2024</td>
                                <td>#Paypal0001</td>
                                <td><span class="badge badge-soft-secondary">Black</span></td>
                                <td>$1000</td>
                                <td>1.2%</td>
                                <td>$2000</td>
                                <td><span class="badge badge-soft-success">Live</span></td>
                            </tr>
                            <tr>
                                <td>31/05/2024</td>
                                <td>#Paypal0002</td>
                                <td><span class="badge badge-soft-primary">White</span></td>
                                <td>$2000</td>
                                <td>0.2%</td>
                                <td>$5000</td>
                                <td><span class="badge badge-soft-success">Live</span></td>
                            </tr>
                            <tr>
                                <td>05/01/2024</td>
                                <td>#Paypal0003</td>
                                <td><span class="badge badge-soft-primary">White</span></td>
                                <td>$2000</td>
                                <td>0.2%</td>
                                <td>$5000</td>
                                <td><span class="badge badge-soft-danger">Down</span></td>
                            </tr>
                            <tr>
                                <td>01/01/2024</td>
                                <td>#Stripe0001</td>
                                <td><span class="badge badge-soft-primary">White</span></td>
                                <td>$1000</td>
                                <td>2.2%</td>
                                <td>$1200</td>
                                <td><span class="badge badge-soft-success">Live</span></td>
                            </tr>
                            <tr>
                                <td>01/01/2024</td>
                                <td>#Paypal0004</td>
                                <td><span class="badge badge-soft-secondary">Black</span></td>
                                <td>$100</td>
                                <td>0.2%</td>
                                <td>$2000</td>
                                <td><span class="badge badge-soft-danger">Down</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="{{ asset('theme/assets/pages/jquery.sales_dashboard.init.js') }}"></script>

    <script>
        //date range picker
        $(document).ready(() => {
            let dateFilter = $('#db_date_filter');
            let urlParams = new URLSearchParams(window.location.search);
            let startDate = urlParams.get('start_date') ? moment(urlParams.get('start_date')) : moment().subtract(1, 'M');
            let endDate = urlParams.get('end_date') ? moment(urlParams.get('end_date')) : moment();

            dateFilter.html(moment(startDate).format('YYYY-MM-DD') + ' - ' + moment(endDate).format('YYYY-MM-DD'));

            dateFilter.daterangepicker({
                startDate: startDate,
                endDate: endDate,
                locale: {
                    format: 'YYYY-MM-DD',
                },
            }, (startDate, endDate) => {
                let newUrl = new URL(window.location.href);
                newUrl.searchParams.set('start_date', startDate.format('YYYY-MM-DD'));
                newUrl.searchParams.set('end_date', endDate.format('YYYY-MM-DD'));
                window.location.href = newUrl.href;
            });
        });
    </script>

    <script>
        // bar main chart
        document.addEventListener("DOMContentLoaded", function () {
            let chartData = @json($chartData);
            var options = {
                chart: {
                    type: 'bar',
                    height: 387,
                },
                series: [{
                    name: 'Revenue',
                    data: chartData.data,
                }],
                xaxis: {
                    categories: chartData.categories
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return "$" + value.toLocaleString();
                        }
                    },
                },
                tooltip: {
                    y: {
                        formatter: function (value) {
                            return "$" + value.toLocaleString();
                        }
                    }
                }
            }

            var chart = new ApexCharts(document.querySelector("#Revenue_Status"), options);
            chart.render();
        });
    </script>

    <script>
        // under totals, area chart
        document.addEventListener("DOMContentLoaded", function () {
            let chartData = @json($chartData);

            var options = {
                chart: {
                    type: 'area',
                    height: 80,
                    sparkline: {
                        enabled: true
                    }
                },
                series: [{
                    name: 'Revenue',
                    data: chartData.data
                }],

                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        stops: [0, 90, 100]
                    }
                },
                colors: ['#2E93fA'],
                xaxis: {
                    categories: chartData.categories
                },
                tooltip: {
                    y: {
                        formatter: function (value) {
                            return "$" + value.toLocaleString();
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#area_chart"), options);
            chart.render();
        });
    </script>

    <script>
        // dispute chart
        document.addEventListener("DOMContentLoaded", function () {
            let disputeChartData = @json($chartDataDispute);

            var options = {
                chart: {
                    type: 'donut',
                    height: 270,
                },
                labels: disputeChartData.labels,
                series: disputeChartData.data,

                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    enabled: true,
                    y: {
                        formatter: function (val) {
                            return val.toFixed(2) + "%";
                        }
                    }
                },
                colors: ['#28a745', '#ffc107', '#dc3545'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '82%',
                        },
                        expandOnClick: false,
                    }
                },
            };

            var disputeChart = new ApexCharts(document.querySelector("#dispute_chart"), options);
            disputeChart.render();
        });
    </script>
@endpush
