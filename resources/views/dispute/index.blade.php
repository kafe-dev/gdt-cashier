@extends('_layouts.main')

@push('page')
    Manage Dispute
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('manage-dispute') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Dispute List</h4>
                        <p class="text-muted mb-0">
                            This is a list of all dispute in the system.
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('app.dispute.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3 my-1">
                                <input type="text" name="dispute_id" class="form-control" placeholder="Dispute ID" value="{{ request('dispute_id') }}">
                            </div>
                            <div class="col-md-3 my-1">
                                <input type="text" name="merchant_id" class="form-control" placeholder="Merchant ID" value="{{ request('merchant_id') }}">
                            </div>
                            <div class="col-md-3 my-1">
                                <input type="text" name="reason" class="form-control" placeholder="Lý do" value="{{ request('reason') }}">
                            </div>
                            <div class="col-md-3 my-1">
                                <input type="text" name="create_date_range" id="create_date_range" class="form-control" placeholder="Chọn khoảng thời gian" value="{{ request('date_range') }}">
                            </div>
                            <div class="col-md-3 my-1">
                                <select name="status" class="form-control">
                                    <option value="">Trạng thái</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Tìm kiếm</button>
                        <a href="{{route('app.dispute.index')}}" class="btn btn-danger mt-2">Bỏ lọc</a>
                    </form>
                    <table class="table table-bordered">
                        <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Dispute ID</th>
                            <th>Merchant ID</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($disputes as $dispute)
                            <tr>
                                <td>{{ $dispute->id }}</td>
                                <td>{{ $dispute->dispute_id }}</td>
                                <td>{{ $dispute->merchant_id }}</td>
                                <td>{{ $dispute->status }}</td>
                                <td>{{ $dispute->reason }}</td>
                                <td>{{ $dispute->created_at }}</td>
                                <td>
                                    @php
                                        $btnView           = '<a href="' . route('app.dispute.show', ['id' => $dispute->id]) . '" class="btn btn-sm btn-info m-1" title="View"><i class="fa fa-eye"></i></a>';
                                        $btnRedirectPaypal = '<a href="https://www.sandbox.paypal.com/resolutioncenter/view/' . $dispute->dispute_id . '" class="btn btn-sm btn-primary m-1" title="View" target="_blank"><i class="fab fa-paypal"></i></a>';

                                        echo $btnView . ' ' . $btnRedirectPaypal;
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $disputes->onEachSide(1)->links('pagination::bootstrap-5') }} <!-- Hiển thị phân trang -->
                </div>
            </div>
        </div>
    </div>

@endsection
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Khởi tạo Daterangepicker khi trang đã load xong
        $('#create_date_range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear',
                applyLabel: 'Chọn',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12']
            }
        });

        // Cập nhật giá trị khi chọn ngày
        $('#create_date_range').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        // Xóa giá trị khi nhấn "Clear"
        $('#create_date_range').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    });
</script>


