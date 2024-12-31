@extends('base')

@section('title')
    @yield('page') | {{ config('app.name') }}
@endsection

@section('body')
    <div id="x-blank">
        @yield('content')
    </div>
@endsection
