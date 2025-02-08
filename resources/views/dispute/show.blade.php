@php
    use App\Helpers\TimeHelper;use App\Models\Paygate;use App\Utils\ActionWidget;
@endphp

@extends('_layouts.main')

@push('page')
    Information Dispute
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('show-dispute', $dispute) }}
@endpush

@php
    $paygate = Paygate::find($dispute->paygate_id);
    $transaction = $dispute_arr['disputed_transactions'][0] ?? [];
    $shipping_address = $transaction_arr['transaction_details'][0]['shipping_info']['address'] ?? [];
    $buyer_name = $transaction['buyer']['name'] ?? '';
    $messages = $dispute_arr['messages']??[];
@endphp

@section('content')
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dispute ID: {{ $dispute->id }}</h4>
                    <p class="text-muted mb-0">Details of dispute ID: {{ $dispute->id }}</p>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ([
                            'Case ID' => $dispute_arr['dispute_id'] ?? 'N/A',
                            'Disputed amount' => "$" . ($dispute_arr['dispute_amount']['value'] ?? 0) . " " . ($dispute_arr['dispute_amount']['currency_code'] ?? 'USD'),
                            'Buyer info' => $buyer_name . '<br>' . ($transaction_arr['transaction_details'][0]['payer_info']['email_address'] ?? ''),
                            'Shipping address' => ($shipping_address['line1'] ?? '') . ', ' . ($shipping_address['line2'] ?? '') . '<br>' . ($shipping_address['city'] ?? '') . '<br>' . ($shipping_address['postal_code'] ?? ''),
                            'Date reported' => $dispute_arr['create_time'] ?? 'N/A',
                            'Invoice ID' => $transaction['invoice_number'] ?? 'N/A',
                            'Transaction ID' => $transaction['seller_transaction_id'] ?? 'N/A',
                            'Transaction amount' => "$" . ($transaction['gross_amount']['value'] ?? 0) . " " . ($transaction['gross_amount']['currency_code'] ?? 'USD'),
                        ] as $label => $value)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">{{ $label }}:</span>
                                <span class="text-right">{!! $value !!}</span>
                            </li>
                        @endforeach
                    </ul>
                    <hr>
                    <h4 class="card-title mb-3">Your conversation with {{$buyer_name}}</h4>
                    @if($dispute_arr['status'] ==='WAITING_FOR_SELLER_RESPONSE')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Warning!</strong> You have a message from {{$buyer_name}}. Respond as soon as possible.
                        </div>
                    @endif

                    <ul class="list-unstyled mb-0 mt-4">

                        @foreach ($messages as $message)
                            @if($message['posted_by'] == 'BUYER')
                                <li>
                                    <div class="row mb-3">
                                        <div class="col-auto">
                                            <img src="https://ui-avatars.com/api/?name={{$buyer_name}}&background=random" alt="" class="thumb-md rounded-circle">
                                        </div>
                                        <div class="col">
                                            <div class="bg-light rounded p-3">
                                                <div class="d-flex justify-content-between">
                                                    <p class="text-dark fw-semibold mb-2">{{$buyer_name}}</p>
                                                    <span class="text-muted"><i class="far fa-clock me-1"></i>{{TimeHelper::getTimeAgo($message['time_posted'])}}</span>
                                                </div>
                                                <p>{{$message['content']}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @else
                                <div class="row justify-content-end mb-3">
                                    <div class="col">
                                        <div class="bg-primary text-white rounded p-3">
                                            <div class="d-flex justify-content-between">
                                                <p class="fw-semibold mb-2">You</p>
                                                <span class="text-light"><i class="far fa-clock me-1"></i>{{TimeHelper::getTimeAgo($message['time_posted'])}}</span>
                                            </div>
                                            <p>{{$message['content']}}</p>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <img src="https://ui-avatars.com/api/?name=Seller&amp;background=random" alt="" class="thumb-md rounded-circle">
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer">
                    {{ ActionWidget::renderGoBackBtn('Go Back', 'btn btn-danger') }}
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Action
                        <i class="las la-angle-right ms-1"></i></button>
                    <div class="dropdown-menu" style="">
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#send-message-dispute-modal">Send message about dispute to other party</a>
                        <a class="dropdown-item" href="#">Provide evidence</a>
                        <a class="dropdown-item" href="#">Escalate dispute to claim</a>
                        <a class="dropdown-item" href="#">Make offer to resolve dispute</a>
                        <a class="dropdown-item" href="#">Accept claim</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12"></div>
    </div>
    <div class="modal fade" id="send-message-dispute-modal" tabindex="-1" aria-labelledby="send-message-dispute-modal-label" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="send-message-dispute-modal-label">Send message about dispute to other party</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                <div class="modal-body">
                    <div class="row">
                        <form action="{{ route('app.dispute.send-message') }}" method="POST">
                            @csrf
                            <input type="hidden" class="form-control" id="paygate_id" name="paygate_id" value="{{$paygate->id}}" required readonly>
                            <input type="hidden" class="form-control" id="dispute_id" name="dispute_id" value="{{$dispute->id}}" required readonly>

                            <div class="form-group">
                                <label for="dispute_id">Dispute Code:</label>
                                <input type="text" class="form-control" id="dispute_code" name="dispute_code" value="{{$dispute_arr['dispute_id']??'N/A'}}" required readonly>
                            </div>

                            <div class="form-group">
                                <label for="message">Message:</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </form>
                    </div><!--end row-->
                </div><!--end modal-body-->
            </div><!--end modal-content-->
        </div><!--end modal-dialog-->
    </div>
@endsection
