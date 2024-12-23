@extends('base')

@section('body')
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-body">

                            {{-- Main section --}}
                            @yield('form')
                            {{-- Main section --}}

                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-12 text-center">
                            <p class="text-muted mb-0">
                                <span class="text-muted">Copyright &copy; {{ date('Y') }}</span>
                                <span class="text-muted">|</span>
                                <a href="{{ config('app.url') }}" class="text-primary">{{ config('app.name') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
