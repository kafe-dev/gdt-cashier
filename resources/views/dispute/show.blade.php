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
                                                @foreach($offers as $offer)
                                                    @if($offer['actor'] == 'BUYER' && $offer['offer_time'] === $message['time_posted'])
                                                        <span class="@if($offer['event_type'] == "DENIED") text-danger @else text-success @endif" style="font-size: larger">Offer: {{ $offer['event_type'] }}</span>
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
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#escalate-dispute-modal">Escalate dispute to claim</a>
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
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </form>
                    </div><!--end row-->
                </div><!--end modal-body-->
            </div><!--end modal-content-->
        </div><!--end modal-dialog-->
    </div>

    {{--    Begin Make Offer Modal --}}
    <div class="modal fade" id="make-offer-dispute-modal" tabindex="-1" aria-labelledby="make-offer-dispute-modal-label"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('app.dispute.makeOffer', $dispute->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title m-0" id="make-offer-dispute-modal-label">Make offer to resolve
                            dispute</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div><!--end modal-header-->
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Offer Type <span class="text-danger">*</span></label>
                                <select id="offer_type" name="offer_type" class="form-select" required>
                                    <option value="REFUND" selected>Refund</option>
                                    <option value="REFUND_WITH_RETURN">Refund with Return</option>
                                    <option value="REFUND_WITH_REPLACEMENT">Refund with Replacement</option>
                                    <option value="REPLACEMENT_WITHOUT_REFUND">Replacement without Refund</option>
                                </select>
                            </div>

                            <div id="amount-section">
                                <div class="mb-3">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" maxlength="32" min="0.01"  step="0.01"
                                           class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                                    <select name="currency" class="form-select" required>
                                        <option value="USD" selected>USD - United States dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="RUB">RUB - Russian ruble</option>
                                        <option value="SGD">SGD - Singapore dollar</option>
                                        <option value="AUD">AUD - Australian dollar</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note <span class="text-danger">*</span></label>
                                <textarea name="note" maxlength="2000" class="form-control" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Invoice ID (Optional)</label>
                                <input type="text" name="invoice_id" maxlength="127" class="form-control">
                            </div>
                            <div id="return-address-section">
                                <div class="mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" maxlength="300" class="form-control mb-2" placeholder="Address">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Country Code <span class="text-danger">*</span></label>
                                    <input type="text" name="country_code" maxlength="2" class="form-control mb-2"
                                           placeholder="Country Code">
                                </div>
                            </div>

                        </div><!--end row-->
                    </div><!--end modal-body-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                    </div><!--end modal-footer-->
                </form>
            </div><!--end modal-content-->
        </div><!--end modal-dialog-->
    </div>
    {{--    End Make Offer Modal--}}


    <div class="modal fade" id="escalate-dispute-modal" tabindex="-1" aria-labelledby="escalate-dispute-modal-label" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="escalate-dispute-modal-label">Escalate dispute to claim</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                <div class="modal-body">
                    <div class="row">
                        <form action="{{ route('app.dispute.escalate') }}" method="POST">
                            @csrf
                            <input type="hidden" class="form-control" id="paygate_id" name="paygate_id" value="{{$paygate->id}}" required readonly>
                            <input type="hidden" class="form-control" id="dispute_id" name="dispute_id" value="{{$dispute->id}}" required readonly>

                            <div class="form-group">
                                <label for="dispute_id">Dispute Code:</label>
                                <input type="text" class="form-control" id="dispute_code" name="dispute_code" value="{{$dispute_arr['dispute_id']??'N/A'}}" required readonly>
                            </div>

                            <div class="form-group">
                                <label for="message">Note:</label>
                                <textarea class="form-control" id="note" name="note" rows="4" required></textarea>
                            </div>
                            <div class="float-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div><!--end row-->
                </div><!--end modal-body-->
            </div><!--end modal-content-->
        </div><!--end modal-dialog-->
    </div>

@endsection

@push('custom-scripts')
    <script>
        // Make offer modal
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("make-offer-dispute-modal");
            const offerTypeSelect = modal.querySelector("select[name='offer_type']");
            const amountSection = modal.querySelector("#amount-section");
            const returnAddressSection = modal.querySelector("#return-address-section");
            const amountInput = modal.querySelector("input[name='amount']");
            const currencySelect = modal.querySelector("select[name='currency']")
            const addressInput = modal.querySelector("input[name='address']");
            const countryCodeInput =  modal.querySelector("input[name='country_code']");

            function updateFormFields() {
                const selectedType = offerTypeSelect.value;

                amountSection.style.display = "none";
                returnAddressSection.style.display = "none";
                amountInput.removeAttribute("required");
                currencySelect.removeAttribute("required");
                addressInput.removeAttribute("required");
                countryCodeInput.removeAttribute("required");

                // REFUND và REFUND_WITH_REPLACEMENT
                if (selectedType === "REFUND" || selectedType === "REFUND_WITH_REPLACEMENT" || selectedType === "REFUND_WITH_RETURN") {
                    amountSection.style.display = "block";
                    amountInput.setAttribute("required", "required");
                    currencySelect.setAttribute("required", "required");
                }

                // REFUND_WITH_RETURN
                if (selectedType === "REFUND_WITH_RETURN") {
                    returnAddressSection.style.display = "block";
                    addressInput.setAttribute("required", "required");
                    countryCodeInput.setAttribute("required", "required");
                }
            }

            // Cập nhật khi thay đổi Offer Type
            offerTypeSelect.addEventListener("change", updateFormFields);

            // Gọi khi trang load lần đầu
            updateFormFields();
        });
    </script>
@endpush
