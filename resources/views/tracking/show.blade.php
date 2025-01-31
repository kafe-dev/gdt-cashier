@extends('_layouts.main')

@push('page')
    View Order Tracking ID: {{ $orderTracking->id }}
@endpush

@push('breadcrumbs')
{{--    {{ Breadcrumbs::render() }}--}}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Order Tracking ID: {{ $orderTracking->id }}</h4>
                    <p class="text-muted mb-0">This table below is showing the detail information of order tracking ID: {{ $orderTracking->id }}</p>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">ID:</span>
                            </div>
                            <span>{{ $orderTracking->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Order ID:</span>
                            </div>
                            <span>{{ $orderTracking->order_id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Tracking number:</span>
                            </div>
                            <span>{{ $orderTracking->tracking_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Courier code:</span>
                            </div>
                            <span>{{ $orderTracking->courier_code }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Tracking status:</span>
                            </div>
                            {!!
                                $status = match ($orderTracking->tracking_status) {
                                    'inforeceived' => '<span class="badge badge-soft-info">Info Received</span>',
                                    'transit' => '<span class="badge badge-soft-primary">In Transit</span>',
                                    'pickup' => '<span class="badge badge-soft-primary">Out for Delivery</span>',
                                    'undelivered' => '<span class="badge badge-soft-warning">Failed Attempt</span>',
                                    'delivered' => '<span class="badge badge-soft-success">Delivered</span>',
                                    'exception' => '<span class="badge badge-soft-warning">Exception</span>',
                                    'expired' => '<span class="badge badge-soft-dark">Expired</span>',
                                    'notfound' => '<span class="badge badge-soft-secondary">Not Found</span>',
                                    'pending' => '<span class="badge badge-soft-purple">Pending</span>',
                                    default => '<span class="badge badge-soft-info">Unknown</span>',
                                }
                            !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Tracking data:</span>
                            </div>
                            <span>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                                    Show
                                </button>
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title m-0" id="exampleModalCenterTitle">Tracking data</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div><!--end modal-header-->
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <pre>{{ json_encode($json_tracking_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    </div><!--end row-->
                                                </div><!--end modal-body-->
                                            </div><!--end modal-content-->
                                        </div><!--end modal-dialog-->
                                    </div>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Type:</span>
                            </div>
                            {!!
                                $type = match ($orderTracking->type) {
                                    \App\Models\OrderTracking::TYPE_OPEN => '<span class="badge badge-soft-primary">'.\App\Models\OrderTracking::TYPES[$orderTracking->type].'</span>',
                                    \App\Models\OrderTracking::TYPE_CLOSED => '<span class="badge badge-soft-dark">'.\App\Models\OrderTracking::TYPES[$orderTracking->type].'</span>',
                                    default => '<span class="badge badge-soft-secondary">'.\App\Models\OrderTracking::TYPES[$orderTracking->type].'</span>',
                                };
                            !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Closed At:</span>
                            </div>
                            {!! ! empty($orderTracking->closed_at) ? '<span class="x-has-time-converter">'.$orderTracking->closed_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Last checked At:</span>
                            </div>
                            {!! ! empty($orderTracking->last_checked_at) ? '<span class="x-has-time-converter">'.$orderTracking->last_checked_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
{{--                        <li class="list-group-item d-flex justify-content-between align-items-center">--}}
{{--                            <div>--}}
{{--                                <span class="text-muted">Exported At:</span>--}}
{{--                            </div>--}}
{{--                            {!! ! empty($orderTracking->exported_at) ? '<span class="x-has-time-converter">'.$orderTracking->exported_at->format(config('app.date_format')).'</span>' : '-' !!}--}}
{{--                        </li>--}}
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Created At:</span>
                            </div>
                            {!! ! empty($orderTracking->created_at) ? '<span class="x-has-time-converter">'.$orderTracking->created_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Updated At:</span>
                            </div>
                            {!! ! empty($orderTracking->updated_at) ? '<span class="x-has-time-converter">'.$orderTracking->updated_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    {{ \App\Utils\ActionWidget::renderDeleteBtn($orderTracking->id, route('app.tracking.delete', ['id' => $orderTracking->id]), 'Delete', 'btn btn-danger') }}
                    {{ \App\Utils\ActionWidget::renderGoBackBtn('Go Back', 'btn btn-primary') }}
                </div>
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
