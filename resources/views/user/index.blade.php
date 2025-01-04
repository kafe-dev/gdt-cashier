@extends('_layouts.main')

@push('page')
    Manage User
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('manage-user') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">User List</h4>
                        <p class="text-muted mb-0">
                            This is a list of all users in the system.
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
