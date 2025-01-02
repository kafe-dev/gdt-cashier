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
                        <h4 class="card-title">Default Datatable</h4>
                        <p class="text-muted mb-0">DataTables has most features enabled by
                            default, so all you need to do to use it with your own tables is to call
                            the construction function: <code>$().DataTable();</code>.
                        </p>
                    </div>
                    <div id="user-date-filter" class="date-range-filter float-end">
                        <i class="fa fa-calendar"></i>
                        <span></span>
                        <i class="fa fa-caret-down"></i>
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
