@php use Carbon\Carbon; @endphp
@php
    /* @var \App\Models\Dispute $dispute */
    /* @var \App\Models\Paygate $paygate */
@endphp
@extends('_layouts.main')

@push('page')
    Information Dispute
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('show-dispute',$dispute) }}
@endpush

@php
    $paygate = \App\Models\Paygate::find($dispute->paygate_id);
@endphp

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dispute ID: {{ $dispute->id }}</h4>
                    <p class="text-muted mb-0">This table below is showing the detail information of dispute ID: {{ $dispute->id }}</p>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">ID:</span>
                            </div>
                            <span>{{ $dispute->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Dispute ID:</span>
                            </div>
                            <span>{{ $dispute->dispute_id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Paygate ID:</span>
                            </div>
                            <span>{{ $paygate->name??'' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Merchant ID:</span>
                            </div>
                            <span>{{ $dispute->merchant_id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Reason:</span>
                            </div>
                            <span>{{ $dispute->reason ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Status:</span>
                            </div>
                            <span class="badge badge-soft-{{ $dispute->status === 'closed' ? 'secondary' : 'primary' }}">{{ $dispute->status }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Dispute State:</span>
                            </div>
                            <span>{{ $dispute->dispute_state ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Dispute Amount:</span>
                            </div>
                            <span>{{ $dispute->dispute_amount_value }} {{ $dispute->dispute_amount_currency }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Dispute Life Cycle Stage:</span>
                            </div>
                            <span>{{ $dispute->dispute_life_cycle_stage ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Dispute Channel:</span>
                            </div>
                            <span>{{ $dispute->dispute_channel ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Seller Response Due Date:</span>
                            </div>
                            {!! !empty($dispute->seller_response_due_date) ? '<span class="x-has-time-converter">'.$dispute->seller_response_due_date->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Link:</span>
                            </div>
                            <span><a href="{{ $dispute->link }}" target="_blank" class="text-primary">{{$dispute->link}}</a></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Created At:</span>
                            </div>
                            {!! !empty($dispute->created_at) ? '<span class="x-has-time-converter">'.$dispute->created_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Updated At:</span>
                            </div>
                            {!! !empty($dispute->updated_at) ? '<span class="x-has-time-converter">'.$dispute->updated_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                    </ul>

                </div>
                <div class="card-footer">
                    {{ \App\Utils\ActionWidget::renderGoBackBtn('Go Back', 'btn btn-primary') }}
                </div>
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
