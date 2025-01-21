@extends('_layouts.main')

@push('page')
    Create Paygates
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('create-user') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Paygate</h4>
                    <p class="text-muted mb-0">Fill out the form below to create a new paygate.</p>
                    <p class="text-muted mb-0">Field with the (<span class="text-danger">*</span>) is required.</p>
                </div>
                @include('paygate._form')
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
@extends('_layouts.main')

@push('page')
    Create User
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('create-paygate') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Paygate</h4>
                    <p class="text-muted mb-0">Fill out the form below to create a new paygate.</p>
                    <p class="text-muted mb-0">Field with the (<span class="text-danger">*</span>) is required.</p>
                </div>
                @include('paygate._form_create',compact('paygate'))
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
