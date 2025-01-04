@extends('base')

@section('title')
    @stack('page') | {{ config('app.name') }}
@endsection

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('theme/plugins/jvectormap/jquery-jvectormap-2.0.2.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/assets/css/metisMenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/plugins/daterangepicker/daterangepicker.css') }}">

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

    @stack('custom-scripts')
@endpush
