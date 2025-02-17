@php
    $evidence_type = [
        'OTHER',
        'PROOF_OF_REFUND'
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
                        <label for="evidence_type" class="form-label">Evidence Type</label>
                        <select class="form-control" id="evidence_type" name="evidence_type" required>
                            <option value="">Select Evidence Type</option>
                            @foreach($evidence_type as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Note (Chỉ hiển thị khi chọn OTHER) -->
                    <div id="note_field">
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <input type="text" class="form-control" id="note" name="note"
                                   value="" placeholder="Enter Note">
                        </div>
                    </div>

                    <!-- Refund ID (Chỉ hiển thị khi chọn PROOF_OF_REFUND) -->
                    <div id="refund_fields">
                        <div class="mb-3">
                            <label for="refund_id" class="form-label">Refund ID</label>
                            <input type="text" class="form-control" id="refund_id" name="refund_id"
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
        let noteField = document.getElementById("note_field");
        let refundFields = document.getElementById("refund_fields");

        function toggleFields() {
            let selectedValue = evidenceType.value;

            if (selectedValue === "OTHER") {
                noteField.style.display = "block";
                refundFields.style.display = "none";
            } else if (selectedValue === "PROOF_OF_REFUND") {
                noteField.style.display = "none";
                refundFields.style.display = "block";
            } else {
                noteField.style.display = "none";
                refundFields.style.display = "none";
            }
        }

        // Gọi hàm ngay khi trang tải lần đầu (ẩn tất cả)
        toggleFields();

        // Lắng nghe sự kiện thay đổi trên dropdown Evidence Type
        evidenceType.addEventListener("change", toggleFields);
    });
</script>
