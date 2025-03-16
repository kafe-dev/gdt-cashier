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
    $offers = $dispute_arr['offer']['history']??[];
    $links = $dispute_arr['links']??[];
    $actions = array_map(fn($link) => $link['rel'], $links);
    $evidences = $dispute_arr['evidences'];
    $action_dispute = [
        'accept_claim' => '<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#accept-claim-modal"><i class="fas fa-angle-right fa-xs me-2"></i> Accept claim</a>',
        'provide_evidence' => '<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#provide-evidence-modal"><i class="fas fa-angle-right fa-xs me-2"></i> Provide evidence</a>',
        'make_offer' => '<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#make-offer-dispute-modal"><i class="fas fa-angle-right fa-xs me-2"></i> Make offer to resolve dispute</a>',
        'escalate' => '<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#escalate-dispute-modal"><i class="fas fa-angle-right fa-xs me-2"></i> Escalate dispute to claim</a>',
        'send_message' => '<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#send-message-dispute-modal"><i class="fas fa-angle-right fa-xs me-2"></i> Send message about dispute to other party</a>',
        'acknowledge_return_item' => '<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#acknowledge-returned-dispute-modal"><i class="fas fa-angle-right fa-xs me-2"></i> Acknowledge returned item</a>',
    ];
    $action_dispute_btn = [];
    foreach ($actions as $action){
        if(isset($action_dispute[$action])){
            $action_dispute_btn[$action] = $action_dispute[$action];
        }
    }

@endphp
@section('content')
    <div class="row">
        <div class="col-md-6 col-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
{{--                <div class="card-header">--}}
{{--                    <h4 class="card-title">Dispute ID: {{ $dispute->id }}</h4>--}}
{{--                    <p class="text-muted mb-0">Details of dispute ID: {{ $dispute->id }}</p>--}}
{{--                </div>--}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">Dispute ID: {{ $dispute->dispute_id }}</h4>
                        <p class="text-muted mb-0">Details of dispute ID: {{ $dispute->dispute_id }}</p>
                    </div>
                    {{ ActionWidget::renderGoBackBtn('<i class="las la-angle-left ms-1"></i> Go Back', 'btn btn-danger') }}
                </div>

                <div class="card-body">
                    @if(!empty($action_dispute_btn))
                        <div class="border p-3">
                            <p>To help us resolve your case as quickly as possible we’ll need you to respond by <b>{{$dispute_arr['seller_response_due_date']??''}}</b>.</p>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle rounded-pill px-5" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    Action
                                    <i class="las la-angle-right ms-1"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @foreach($action_dispute_btn as $_btn)
                                        {!! $_btn !!}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <ul class="list-group">
                        @foreach ([
                            'Case ID' => $dispute_arr['dispute_id'] ?? 'N/A',
                            'Status' => $dispute_arr['status'] ?? 'N/A',
                            'reason' => $dispute_arr['reason'] ?? 'N/A',
                            'dispute_life_cycle_stage'=>$dispute_arr['dispute_life_cycle_stage']??'N/A',
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

                        @if(!empty($evidences))
                            @foreach($evidences as $label => $value)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Evidences: {!! $value['evidence_type'] !!}</span>
                                    <span class="text-right">
                                        @isset($value['notes'])
                                            Note: {{ $value['notes'] }}<br>
                                        @endisset
                                        @if(!empty($value['evidence_info']['tracking_info']))
                                            @php $tracking_info = $value['evidence_info']['tracking_info'][0]; @endphp
                                            Carrier Name: {{ $tracking_info['carrier_name'] }}<br>
                                            Tracking Number: {{ $tracking_info['tracking_number'] }}<br>
                                        @endif
                                        @isset($value['source'])
                                            Source: {{ $value['source'] }}<br>
                                        @endisset
                                    </span>
                                </li>
                            @endforeach
                        @endif

                    </ul>
                    <hr>
                    <h4 class="card-title mb-3">Your conversation with {{$buyer_name}}</h4>
                    @if($dispute_arr['status'] ==='WAITING_FOR_SELLER_RESPONSE')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Warning!</strong> You have a message from {{$buyer_name}}. Respond as soon as
                            possible.
                        </div>
                    @endif

                    <ul class="list-unstyled mb-0 mt-4">

                        @foreach ($messages as $message)
                            @if($message['posted_by'] == 'BUYER')
                                <li>
                                    <div class="row mb-3">
                                        <div class="col-auto">
                                            <img
                                                src="https://ui-avatars.com/api/?name={{$buyer_name}}&background=random"
                                                alt="" class="thumb-md rounded-circle">
                                        </div>
                                        <div class="col">
                                            <div class="bg-light rounded p-3">
                                                <div class="d-flex justify-content-between">
                                                    <p class="text-dark fw-semibold mb-2">{{$buyer_name}}</p>
                                                    <span class="text-muted"><i class="far fa-clock me-1"></i>{{TimeHelper::getTimeAgo($message['time_posted'])}}</span>
                                                </div>
                                                <p>{{$message['content']}}</p>
                                                @foreach($offers as $offer)
                                                    @if($offer['actor'] == 'BUYER' && $offer['offer_time'] === $message['time_posted'])
                                                        <span
                                                            class="@if($offer['event_type'] == "DENIED") text-danger @else text-success @endif"
                                                            style="font-size: larger">Offer: {{ $offer['event_type'] }}</span>
                                                    @endif
                                                @endforeach
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
                                        <img src="https://ui-avatars.com/api/?name=Seller&amp;background=random" alt=""
                                             class="thumb-md rounded-circle">
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
        </div>
    </div>

    {{--    Begin Send Message Modal --}}
    @include('dispute._modal-send-message')
    {{--    End Send Message Modal --}}

    {{--    Begin Make Offer Modal --}}
    @include('dispute._modal-make-offer')
    {{--    End Make Offer Modal--}}

    {{--    Begin Escalate Modal --}}
    @include('dispute._modal-escalate')
    {{--    End Escalate Modal--}}

    {{--    Begin Acknowledge Return Modal--}}
    @include('dispute._modal-acknowledge-return')
    {{--    End Acknowledge Return Modal--}}

    {{--    Begin Accept Claim Modal--}}
    @include('dispute._modal-accept-claim')
    {{--    End Accept Claim Modal--}}

    @include('dispute.form_provider_evidence._modal-provide-evidence',compact('dispute','dispute_arr'))

@endsection
