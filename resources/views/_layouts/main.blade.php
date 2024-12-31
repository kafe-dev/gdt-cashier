@extends('base')

@section('title')
    @yield('page') | {{ config('app.name') }}
@endsection

@section('body')
    @include('_partials.header')
    @include('_partials.sidebar')

    <div id="x-main">
        @yield('content')
    </div>

    @include('_partials.footer')
@endsection
