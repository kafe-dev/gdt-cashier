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
                <span type="button" class="btn btn-outline-primary rounded" style="margin-right: 1rem !important;" id="db_date_filter"></span>

                <a href="#" class="btn btn-sm btn-outline-primary rounded" title="Download report">
                    <i data-feather="download" class="align-self-center icon-xs"></i>
                </a>
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
                        <div id="Revenu_Status" class="apex-charts"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col text-center">
                                    <span class="h4">17</span>
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
                                    <span class="h4">58</span>
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
                                    <span class="h4">520</span>
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
                                    <h6 class="m-0 font-20">$1850.00</h6>
                                    <p class="text-muted mb-0">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto align-self-center">
                            <p class="mb-0"><span class="text-success"><i class="mdi mdi-trending-up"></i>4.8%</span> increase</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="apexchart-wrapper">
                            <div id="dash_spark_1" class="chart-gutters"></div>
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
                        <div id="ana_device" class="apex-charts"></div>
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
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">Open</th>
                                <th class="border-top-0">Resolved</th>
                                <th class="border-top-0">Failed</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>02/01/2025</td>
                                <td>50</td>
                                <td class="text-success">50</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>01/01/2025</td>
                                <td>10</td>
                                <td class="text-success">8</td>
                                <td class="text-danger">2</td>
                            </tr>
                            <tr>
                                <td>31/12/2024</td>
                                <td>0</td>
                                <td class="text-success">0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>30/12/2024</td>
                                <td>5</td>
                                <td class="text-success">1</td>
                                <td class="text-danger">4</td>
                            </tr>
                            <tr>
                                <td>29/12/2024</td>
                                <td>0</td>
                                <td class="text-success">0</td>
                                <td>0</td>
                            </tr>
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
        $(document).ready(() => {
            let dateFilter = $('#db_date_filter');
            let startDate = moment().subtract(1, 'M');
            let endDate = moment();

            dateFilter.html(moment(startDate).format('YYYY-MM-DD') + ' - ' + moment(endDate).format('YYYY-MM-DD'));

            dateFilter.daterangepicker({
                startDate: startDate,
                endDate: endDate,
                locale: {
                    format: 'YYYY-MM-DD',
                },
            }, (startDate, endDate) => {
                dateFilter.html(moment(startDate).format('YYYY-MM-DD') + ' - ' + moment(endDate).format('YYYY-MM-DD'));
            });
        });
    </script>
@endpush
