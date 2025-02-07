@extends('_layouts.main')

@push('page')
    Manage Roles' Permission
@endpush

@push('breadcrumbs')
        {{ Breadcrumbs::render('manage-permission') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Role's permission List</h4>
                        <p class="text-muted mb-0">
                            This is a list of all roles' permission in the system.
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
