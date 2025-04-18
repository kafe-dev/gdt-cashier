@extends('_layouts.main')

@push('page')
    Update User ID: {{ $user->id }}
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('update-user', $user) }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update System User</h4>
                    <p class="text-muted mb-0">Change the fields below to edit.</p>
                    <p class="text-muted mb-0">Field with the (<span class="text-danger">*</span>) is required.</p>
                </div>
                @include('user._form')
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
