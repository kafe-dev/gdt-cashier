@php use App\Models\Paygate; @endphp
@extends('_layouts.main')

@section('content')

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Danh sách Order</h4>
{{--            <p class="card-title-desc">--}}
{{--                Nhấn <a href="{{ route('app.paygate.create') }}" class="text-primary">vào đây</a> để thêm Paygate mới.--}}
{{--            </p>--}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="fw-bold">#</th>
                        <th class="fw-bold">Mã</th>
                        <th class="fw-bold">Trạng thái</th>
                        <th class="fw-bold">Invoice Email</th>
                        <th class="fw-bold">Billing Info</th>
                        <th class="fw-bold">Amount</th>
                        <th class="fw-bold">Currency</th>
                        <th class="fw-bold">Paid Amount</th>
                        <th class="fw-bold">Paid Currency</th>
                        <th class="fw-bold">Link</th>
                        <th class="fw-bold">Created At</th>
                        <th class="fw-bold"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $index => $order)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $order->code }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ $order->invoicer_email_address }}</td>
                            <td>{{ $order->billing_info }}</td>
                            <td>{{ $order->amount }}</td>
                            <td>{{ $order->currency_code }}</td>
                            <td>{{ $order->paid_amount }}</td>
                            <td>{{ $order->paid_currency_code }}</td>
                            <td>{{ $order->link }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end card body -->
    </div>
@endsection
