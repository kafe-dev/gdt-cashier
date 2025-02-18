@php
    $evidence_type = [
        'PROOF_OF_FULFILLMENT',
        'PROOF_OF_REFUND',
        'OTHER',
    ];
@endphp

<div class="modal fade" id="provide-evidence-modal" tabindex="-1"
     aria-labelledby="provide-evidence-modal-label"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('app.dispute.provideEvidence',$dispute->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="provide-evidence-modal-label">Provide Evidence</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->

                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="dispute_code" name="dispute_code"
                               value="{{$dispute->dispute_id}}" required readonly placeholder="Dispute Code">
                    </div>

                    <div class="mb-3">
                        <label for="evidence_type" class="form-label">Evidence Type(<span class="text-danger">*</span>)</label>
                        <select class="form-control" id="evidence_type" name="evidence_type" required>
                            <option value="">Select Evidence Type</option>
                            @foreach($evidence_type as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Carrier Name & Tracking Number (Chỉ hiển thị khi chọn PROOF_OF_FULFILLMENT) -->
                    <div id="fulfillment_fields">
                        <div class="mb-3">
                            <label for="carrier_name" class="form-label">Carrier Name(<span class="text-danger">*</span>)</label>
                            <input type="text" class="form-control" id="carrier_name" name="carrier_name"
                                   value="" placeholder="Enter carrier name">
                        </div>

                        <div class="mb-3">
                            <label for="tracking_number" class="form-label">Tracking Number(<span class="text-danger">*</span>)</label>
                            <input type="text" class="form-control" id="tracking_number" name="tracking_number"
                                   value="" placeholder="Enter tracking number">
                        </div>
                    </div>

                    <!-- Refund ID (Chỉ hiển thị khi chọn PROOF_OF_REFUND) -->
                    <div id="refund_fields">
                        <div class="mb-3">
                            <label for="refund_id" class="form-label">Refund ID(<span class="text-danger">*</span>)</label>
                            <input type="text" class="form-control" id="refund_id" name="refund_id"
                                   value="" placeholder="Enter refund id">
                        </div>
                    </div>

                    <div id="refund_fields">
                        <div class="mb-3">
                            <label for="note" class="form-label">Notes(<span class="text-danger">*</span>)</label>
                            <input type="text" class="form-control" id="note" name="note"
                                   value="" placeholder="Enter refund id">
                        </div>
                    </div>

                </div><!--end modal-body-->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div><!--end modal-footer-->
            </form>

        </div><!--end modal-content-->
    </div><!--end modal-dialog-->
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let evidenceType = document.getElementById("evidence_type");
        let fulfillmentFields = document.getElementById("fulfillment_fields");
        let refundFields = document.getElementById("refund_fields");

        function toggleFields() {
            let selectedValue = evidenceType.value;

            if (selectedValue === "PROOF_OF_FULFILLMENT") {
                fulfillmentFields.style.display = "block";
                refundFields.style.display = "none";
            } else if (selectedValue === "PROOF_OF_REFUND") {
                fulfillmentFields.style.display = "none";
                refundFields.style.display = "block";
            } else {
                fulfillmentFields.style.display = "none";
                refundFields.style.display = "none";
            }
        }

        // Gọi hàm ngay khi trang tải lần đầu (ẩn tất cả)
        toggleFields();

        // Lắng nghe sự kiện thay đổi trên dropdown Evidence Type
        evidenceType.addEventListener("change", toggleFields);
    });
</script>
