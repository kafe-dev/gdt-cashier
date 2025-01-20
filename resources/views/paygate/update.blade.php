@extends('_layouts.main')

@push('page')
    Update Paygate: {{ $paygate->name }}
@endpush

{{--@push('breadcrumbs')--}}
{{--    {{ Breadcrumbs::render('update-paygate') }}--}}
{{--@endpush--}}

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Paygate</h4>
                    <p class="text-muted mb-0">Fill out the form below to update paygate.</p>
                    <p class="text-muted mb-0">Field with the (<span class="text-danger">*</span>) is required.</p>
                </div>
                @include('paygate._form_update',compact('paygate'))
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
