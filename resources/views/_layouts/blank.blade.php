@extends('base')

@section('title')
    @stack('page') | {{ config('app.name') }}
@endsection

@push('body-class')
    {!! 'account-body accountbg' !!}
@endpush

@section('body')
    <div class="container">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="row">
                    <div class="col-lg-5 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 auth-header-box">
                                <div class="text-center p-3">
                                    <a href="{{ route('app.home.index') }}" class="logo logo-admin">
                                        <img src="{{ Vite::asset('resources/assets/images/logo.png') }}" height="50" alt="logo" class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white font-18">Let's Get Started</h4>
                                    <p class="text-muted  mb-0">Login to continue to <b class="text-uppercase">{{ config('app.name') }}</b>.</p>
                                </div>
                            </div>
                            <div class="card-body p-0 pt-3">
                                <div class="tab-content">
                                    <div class="tab-pane active p-3" id="LogIn_Tab" role="tabpanel">
                                        @yield('content')
                                    </div>
                                </div>
                            </div>
                            <div class="card-body bg-light-alt text-center">
                                <span class="text-muted d-none d-sm-inline-block">{{ config('app.name') }} © {{ date('Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
