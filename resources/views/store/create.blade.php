@extends('_layouts.main')

@push('page')
    Create New Store
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('create-store') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Store</h4>
                    <p class="text-muted mb-0">Fill out the form below to create a new store.</p>
                    <p class="text-muted mb-0">Field with the (<span class="text-danger">*</span>) is required.</p>
                </div>
                @include('store._form')
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
