@extends('base')

@section('title')
    @stack('page') | {{ config('app.name') }}
@endsection

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('theme/plugins/jvectormap/jquery-jvectormap-2.0.2.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/assets/css/metisMenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/assets/css/main-style.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    @stack('custom-styles')
@endpush

@section('body')
    @include('_partials.sidebar')

    <div class="page-wrapper">
        @include('_partials.header')

        <div class="page-content">
            <div class="container-fluid">
                @include('_widgets.breadcrumb')

                @yield('content')
            </div>

            @include('_partials.footer')
        </div>
    </div>
@endsection

@push('javascripts')
    <script src="{{ asset('theme/assets/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/moment.js') }}" defer></script>
    <script src="{{ asset('theme/plugins/daterangepicker/daterangepicker.js') }}" defer></script>
    <script src="{{ asset('theme/plugins/apex-charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/jvectormap/jquery-jvectormap-us-aea-en.js') }}"></script>
    <script src="{{ asset('theme/assets/js/app.js') }}"></script>
    <script src="{{ asset('theme/assets/js/main-script.js') }}" defer></script>

    @stack('custom-scripts')
@endpush
