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
                    {{ $dataTable->table() }}
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

@push('custom-scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

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
            <p><strong>VPS Data:</strong></p>
            <p>Ips: <code>${JSON.parse(vpsData).ips}</code></p>
            <p>Username: <code>${JSON.parse(vpsData).username}</code></p>
            <p>Password: <code>${JSON.parse(vpsData).password}</code></p>
            <p><strong>Link Dispute:</strong> <a href="${link}" target="_blank">${link}</a></p>
        `;
            });
        });
        // document.addEventListener("DOMContentLoaded", function() {
        //     // Khởi tạo Daterangepicker khi trang đã load xong
        //     $('#create_date_range').daterangepicker({
        //         autoUpdateInput: false,
        //         locale         : {
        //             format     : 'YYYY-MM-DD',
        //             cancelLabel: 'Clear',
        //             applyLabel : 'Chọn',
        //             daysOfWeek : ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
        //             monthNames : ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12']
        //         }
        //     });
        //
        //     // Cập nhật giá trị khi chọn ngày
        //     $('#create_date_range').on('apply.daterangepicker', function(ev, picker) {
        //         $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        //     });
        //
        //     // Xóa giá trị khi nhấn "Clear"
        //     $('#create_date_range').on('cancel.daterangepicker', function(ev, picker) {
        //         $(this).val('');
        //     });
        // });
    </script>
@endpush
