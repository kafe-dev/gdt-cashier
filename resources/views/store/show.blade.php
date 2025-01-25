@extends('_layouts.main')

@push('page')
    View Store ID: {{ $store->id }}
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('show-store', $store) }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Store ID: {{ $store->id }}</h4>
                    <p class="text-muted mb-0">This table below is showing the detail information of user ID: {{ $store->id }}</p>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">ID:</span>
                            </div>
                            <span>{{ $store->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Shop name:</span>
                            </div>
                            <span>{{ $store->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Owner:</span>
                            </div>
                            <span>{{ $user['username'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Owner email:</span>
                            </div>
                            <span><a href="mailto:{{ $user['email'] }}" class="text-primary">{{ $user['email'] }}</a></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Status:</span>
                            </div>
                            {!!
                                $status = match ($store->status) {
                                    $store::STATUS_INACTIVE => '<span class="badge badge-soft-danger">'.$store::STATUSES[$store->status].'</span>',
                                    $store::STATUS_DRAFT => '<span class="badge badge-soft-secondary">'.$store::STATUSES[$store->status].'</span>',
                                    default => '<span class="badge badge-soft-success">'.$store::STATUSES[$store->status].'</span>',
                                }
                            !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Description:</span>
                            </div>
                                <div style="padding-left: 10px; text-align: right">
                                {!! ! empty($store->description) ? $store->description : '-' !!}
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Created At:</span>
                            </div>
                            {!! ! empty($store->created_at) ? '<span class="x-has-time-converter">'.$store->created_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Updated At:</span>
                            </div>
                            {!! ! empty($store->updated_at) ? '<span class="x-has-time-converter">'.$store->updated_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">API Data:</span>
                            </div>
                            <div style="padding-left: 10px; text-align: right">
                                {!! ! empty($store->api_data) ? $store->api_data : '-' !!}
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    {{ \App\Utils\ActionWidget::renderUpdateBtn(route('app.store.update', ['id' => $store->id]), 'Update', 'btn btn-warning text-white') }}
                    {{ \App\Utils\ActionWidget::renderDeleteBtn($store->id, route('app.store.delete', ['id' => $store->id]), 'Delete', 'btn btn-danger') }}
                    {{ \App\Utils\ActionWidget::renderGoBackBtn('Go Back', 'btn btn-primary') }}
                </div>
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
