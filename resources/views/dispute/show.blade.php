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
                                <span class="text-muted">Case ID:</span>
                            </div>
                            <span>{{ $dispute_arr['dispute_id'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Disputed amount:</span>
                            </div>
                            <span>${{ $dispute_arr['dispute_amount']['value']??0 }} {{ $dispute_arr['dispute_amount']['currency_code']??'USD' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Buyer info:</span>
                            </div>
                            <span>
                                {{ $dispute_arr['disputed_transactions'][0]['buyer']['name']??'' }}<br>

                            </span>
                        </li>

                    </ul>
                    <hr>
                    <h4 class="card-title">Your conversation with John Doe</h4>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Warning!</strong> You have a message from John Doe. Respond as soon as possible..
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <ul class="list-unstyled mb-0 mt-4">
                        <!-- Tin nhắn nhận -->
                        <li>
                            <div class="row">
                                <div class="col-auto">
                                    <img src="https://ui-avatars.com/api/?name=Martin+Luther&background=random" alt="" class="thumb-md rounded-circle">
                                </div>
                                <div class="col">
                                    <div class="bg-light rounded p-3">
                                        <div class="row">
                                            <div class="col">
                                                <p class="text-dark fw-semibold mb-2">Martin Luther</p>
                                            </div>
                                            <div class="col-auto">
                                                <span class="text-muted"><i class="far fa-clock me-1"></i>30 min ago</span>
                                            </div>
                                        </div>
                                        <p>
                                            It is a long established fact that a reader will be distracted by the readable content...
                                        </p>
                                        <a href="#" class="text-primary"><i class="fas fa-reply me-1"></i>Reply</a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Tin nhắn gửi -->
                        <li class="mt-3">
                            <div class="row justify-content-end">
                                <div class="col-auto">
                                    <div class="bg-primary text-white rounded p-3">
                                        <div class="row">
                                            <div class="col">
                                                <p class="fw-semibold mb-2">You</p>
                                            </div>
                                            <div class="col-auto">
                                                <span class="text-white-50"><i class="far fa-clock me-1"></i>Just now</span>
                                            </div>
                                        </div>
                                        <p>
                                            This is a sample sent message using Bootstrap 5.
                                        </p>
                                    </div>
                                </div>
                            </div>
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
