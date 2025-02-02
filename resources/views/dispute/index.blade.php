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
                                <div class="input-group w-auto">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    <input type="text" name="create_date_range" id="create_date_range" class="form-control" placeholder="Chọn khoảng thời gian" value="{{ request('create_date_range') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3 my-1">
                                <input type="text" name="status" class="form-control" placeholder="Status" value="{{ request('status') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2"><i class="mdi mdi-magnify"></i> Tìm kiếm</button>
                        <a href="{{ route('app.dispute.index') }}" class="btn btn-danger mt-2">
                            <i class="mdi mdi-filter-remove"></i> Bỏ lọc
                        </a>
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
                                        $paygate = \App\Models\Paygate::find($dispute->paygate_id);
                                        $data_vps = $paygate? json_encode($paygate->vps_data) : '';
                                        $btnView           = '<a href="' . route('app.dispute.show', ['id' => $dispute->id]) . '" class="btn btn-sm btn-info m-1" title="View"><i class="fa fa-eye"></i></a>';
                                        $btnRedirectPaypal = '<a href="#" class="btn btn-sm btn-primary m-1" title="View" target="_blank" data-bs-toggle="modal" data-bs-target="#dispute-info-paypal" data-vps="' . htmlspecialchars($data_vps). '" data-link="' . htmlspecialchars($dispute->link) . '"><i class="fab fa-paypal"></i></a>';

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
    <div class="modal fade" id="dispute-info-paypal" tabindex="-1" aria-labelledby="dispute-info-paypalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dispute-info-paypalLabel">Thông tin Dispute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var disputeModal = document.getElementById("dispute-info-paypal");

        disputeModal.addEventListener("show.bs.modal", function(event) {
            // Lấy button đã kích hoạt modal
            var button = event.relatedTarget;

            // Lấy dữ liệu từ button
            var vpsData = button.getAttribute("data-vps");
            var link    = button.getAttribute("data-link");


            // Tạo nội dung hiển thị
            var modalBody       = disputeModal.querySelector(".modal-body");
            modalBody.innerHTML = `
            <p><strong>VPS Data:</strong> ${vpsData}</p>
            <p><strong>Link Dispute:</strong> <a href="${link}" target="_blank">${link}</a></p>
        `;
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        // Khởi tạo Daterangepicker khi trang đã load xong
        $('#create_date_range').daterangepicker({
            autoUpdateInput: false,
            locale         : {
                format     : 'YYYY-MM-DD',
                cancelLabel: 'Clear',
                applyLabel : 'Chọn',
                daysOfWeek : ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames : ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12']
            }
        });

        // Cập nhật giá trị khi chọn ngày
        $('#create_date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        // Xóa giá trị khi nhấn "Clear"
        $('#create_date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
