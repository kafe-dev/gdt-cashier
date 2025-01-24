@php
    use App\Models\Paygate;

    /* @var \App\Models\Dispute $dispute */
@endphp
@extends('_layouts.main')

@section('content')

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Danh sách Paygates</h4>
            <p class="card-title-desc">
                Nhấn <a href="{{ route('app.paygate.create') }}" class="text-primary">vào đây</a> để thêm Paygate mới.
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="fw-bold">#</th>
                        <th class="fw-bold">Dispute ID</th>
                        <th class="fw-bold">Buy Transaction ID</th>
                        <th class="fw-bold">Merchant ID</th>
                        <th class="fw-bold">Reason</th>
                        <th class="fw-bold">Status</th>
                        <th class="fw-bold">Dispute State</th>
                        <th class="fw-bold">Dispute Amount Currency</th>
                        <th class="fw-bold">Dispute Amount Value</th>
                        <th class="fw-bold">Dispute Life Cycle Stage</th>
                        <th class="fw-bold">Dispute Channel</th>
                        <th class="fw-bold">Link</th>
                        <th class="fw-bold">Created At</th>
                        <th class="fw-bold">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($disputes as $index => $dispute)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $dispute->dispute_id }}</td>
                            <td>{{ $dispute->buyer_transaction_id }}</td>
                            <td>{{ $dispute->merchant_id}}</td>
                            <td>{{ $dispute->reason }}</td>
                            <td>{{ $dispute->status }}</td>
                            <td>{{ $dispute->dispute_state }}</td>
                            <td>{{ $dispute->dispute_amount_currency }}</td>
                            <td>{{ $dispute->dispute_amount_value }}</td>
                            <td>{{ $dispute->dispute_life_cycle_stage }}</td>
                            <td>{{ $dispute->dispute_channel }}</td>
                            <td>{{ $dispute->link }}</td>
                            <td>{{ $dispute->created_at }}</td>
                            <td>
                                <a href="https://www.sandbox.paypal.com/resolutioncenter/view/{{ $dispute->dispute_id }}"
                                   class="btn btn-light btn-sm"
                                   target="_blank"
                                >In PayPal</a>

                                <a href="{{route('app.dispute.show', $dispute->id)}}"
                                   class="btn btn-light btn-sm">
                                Chi tiết</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end card body -->
    </div>
@endsection
