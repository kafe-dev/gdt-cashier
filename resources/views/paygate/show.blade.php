@extends('_layouts.main')

@push('page')
    View Paygate ID: {{ $paygate->id }}
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('show-paygate', $paygate) }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Paygate ID: {{ $paygate->id }}</h4>
                    <p class="text-muted mb-0">This table below is showing the detail information of paygate ID: {{ $paygate->id }}</p>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">ID:</span>
                            </div>
                            <span>{{ $paygate->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Name:</span>
                            </div>
                            <span>{{ $paygate->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Url:</span>
                            </div>
                            <span><a href="{{ $paygate->url }}" class="text-primary">{{ $paygate->url }}</a></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">API Data:</span>
                            </div>
                            <span class="badge badge-soft-secondary">Secure</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">VPS Data:</span>
                            </div>
                            <span class="badge badge-soft-secondary">Secure</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Type:</span>
                            </div>
                            {!!
                                $type = match ($paygate->type) {
                                    $paygate::TYPE_STRIPE => '<span class="badge badge-soft-success">'.$paygate::TYPE[$paygate->type].'</span>',
                                    default => '<span class="badge badge-soft-primary">'.$paygate::TYPE[$paygate->type].'</span>',
                                };
                            !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Status:</span>
                            </div>
                            {!!
                                $status = match ($paygate->status) {
                                    $paygate::STATUS_INACTIVE => '<span class="badge badge-soft-secondary">'.$paygate::STATUS[$paygate->status].'</span>',
                                    $paygate::STATUS_DRAFT => '<span class="badge badge-soft-danger">'.$paygate::STATUS[$paygate->status].'</span>',
                                    default => '<span class="badge badge-soft-success">'.$paygate::STATUS[$paygate->status].'</span>',
                                }
                            !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Mode:</span>
                            </div>
                            {!!
                                $mode = match ($paygate->mode) {
                                    $paygate::MODE_LIVE => '<span class="badge badge-soft-success">'.$paygate::MODES[$paygate->mode].'</span>',
                                    default => '<span class="badge badge-soft-secondary">'.$paygate::MODES[$paygate->mode].'</span>',
                                };
                            !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Created At:</span>
                            </div>
                            {!! ! empty($paygate->created_at) ? '<span class="x-has-time-converter">'.$paygate->created_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Updated At:</span>
                            </div>
                            {!! ! empty($paygate->updated_at) ? '<span class="x-has-time-converter">'.$paygate->updated_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    {{ \App\Utils\ActionWidget::renderUpdateBtn(route('app.paygate.update', ['id' => $paygate->id]), 'Update', 'btn btn-warning text-white') }}
                    {{ \App\Utils\ActionWidget::renderDeleteBtn($paygate->id, route('app.paygate.delete', ['id' => $paygate->id]), 'Delete', 'btn btn-danger') }}
                    {{ \App\Utils\ActionWidget::renderGoBackBtn('Go Back', 'btn btn-primary') }}
                </div>
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
