@extends('_layouts.main')

@push('page')
    Add Tracking Info
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('add-tracking-info', $orderTracking) }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add tracking info: {{ $orderTracking->id }}</h4>
                    <p class="text-muted mb-0">Fill out the form below to create a new system user.</p>
                    <p class="text-muted mb-0">Field with the (<span class="text-danger">*</span>) is required.</p>
                </div>
                @include('tracking._formTracking')
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
