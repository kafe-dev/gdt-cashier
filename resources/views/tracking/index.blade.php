@extends('_layouts.main')

@push('page')
    Manage Order Tracking
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('manage-order-tracking') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Order Tracking List</h4>
                        <p class="text-muted mb-0">
                            This is a list of all order trackings in the system.
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                    <input type="text" id="daterange_input" class="form-control d-none">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
