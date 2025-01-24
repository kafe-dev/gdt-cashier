@php
    /* @var \App\Models\Dispute $dispute */
@endphp
@extends('_layouts.main')

@push('page')
    Information Dispute
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('show-dispute',$dispute) }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Information Dispute</h4>
                    <p class="text-muted mb-0">Fill out the form below to create a new paygate.</p>
                    <p class="text-muted mb-0">Field with the (<span class="text-danger">*</span>) is required.</p>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ $dispute->dispute_id }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="dispute_id">Dispute ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="dispute_id" name="dispute_id" placeholder="Enter dispute ID" value="{{ $dispute->dispute_id }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="create_time">Create Time</label>
                        <input type="datetime-local" class="form-control" id="create_time" name="create_time" value="{{ $dispute->create_time }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="update_time">Update Time</label>
                        <input type="datetime-local" class="form-control" id="update_time" name="update_time" value="{{ $dispute->update_time }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="buyer_transaction_id">Buyer Transaction ID</label>
                        <input type="text" class="form-control" id="buyer_transaction_id" name="buyer_transaction_id" placeholder="Enter buyer transaction ID" value="{{ $dispute->buyer_transaction_id }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="merchant_id">Merchant ID</label>
                        <input type="text" class="form-control" id="merchant_id" name="merchant_id" placeholder="Enter merchant ID" value="{{ $dispute->merchant_id }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="reason">Reason</label>
                        <input type="text" class="form-control" id="reason" name="reason" placeholder="Enter reason" value="{{ $dispute->reason }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="status">Status</label>
                        <input type="text" class="form-control" id="status" name="status" placeholder="Enter status" value="{{ $dispute->status }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="dispute_state">Dispute State</label>
                        <input type="text" class="form-control" id="dispute_state" name="dispute_state" placeholder="Enter dispute state" value="{{ $dispute->dispute_state }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="dispute_amount_currency">Dispute Amount Currency</label>
                        <input type="text" class="form-control" id="dispute_amount_currency" name="dispute_amount_currency" placeholder="Enter dispute amount currency" value="{{ $dispute->dispute_amount_currency }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="dispute_amount_value">Dispute Amount Value</label>
                        <input type="number" step="0.01" class="form-control" id="dispute_amount_value" name="dispute_amount_value" placeholder="Enter dispute amount value" value="{{ $dispute->dispute_amount_value }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="dispute_life_cycle_stage">Dispute Life Cycle Stage</label>
                        <input type="text" class="form-control" id="dispute_life_cycle_stage" name="dispute_life_cycle_stage" placeholder="Enter dispute life cycle stage" value="{{ $dispute->dispute_life_cycle_stage }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="dispute_channel">Dispute Channel</label>
                        <input type="text" class="form-control" id="dispute_channel" name="dispute_channel" placeholder="Enter dispute channel" value="{{ $dispute->dispute_channel }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="seller_response_due_date">Seller Response Due Date</label>
                        <input type="datetime-local" class="form-control" id="seller_response_due_date" name="seller_response_due_date" value="{{ $dispute->seller_response_due_date }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="link">Link</label>
                        <input type="url" class="form-control" id="link" name="link" placeholder="Enter link" value="{{ $dispute->link }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
