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

                    <div id="file_name_fields">
                        <div class="mb-3">
                            <label for="note" class="form-label">File Name(<span class="text-danger">*</span>)</label>
                            <input type="text" class="form-control" id="file_name" name="file_name"
                                   value="" placeholder="example.jpg">
                        </div>
                    </div>

                    <div id="file_path_fields">
                        <div class="mb-3">
                            <label for="note" class="form-label">File Name(<span class="text-danger">*</span>)</label>
                            <input type="text" class="form-control" id="file_path" name="file_path"
                                   value="" placeholder="https:example.com/example.jpg">
                        </div>
                    </div>

                    <div id="notes_fields">
                        <div class="mb-3">
                            <label for="note" class="form-label">Notes(<span class="text-danger">*</span>)</label>
                            <input type="text" class="form-control" id="note" name="note"
                                   value="" placeholder="Enter notes">
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
        let fileNameFields = document.getElementById("file_name_fields");
        let filePathFields = document.getElementById("file_path_fields");
        let notesFields = document.getElementById("notes_fields");

        function toggleFields() {
            let selectedValue = evidenceType.value;

            // Ẩn tất cả các trường trước khi kiểm tra điều kiện
            fulfillmentFields.style.display = "none";
            refundFields.style.display = "none";
            fileNameFields.style.display = "none";
            filePathFields.style.display = "none";
            notesFields.style.display = "none";

            if (selectedValue === "PROOF_OF_FULFILLMENT") {
                fulfillmentFields.style.display = "block"; // Hiển thị Carrier Name & Tracking Number
                notesFields.style.display = "block"; // Hiển thị Notes
            } else if (selectedValue === "PROOF_OF_REFUND") {
                refundFields.style.display = "block"; // Hiển thị Refund ID
                notesFields.style.display = "block"; // Hiển thị Notes
            } else if (selectedValue === "OTHER") {
                notesFields.style.display = "block"; // Hiển thị Notes
                fileNameFields.style.display = "block"; // Hiển thị File Name
                filePathFields.style.display = "block"; // Hiển thị File Path
            }

        }

        // Ẩn tất cả các trường khi tải trang lần đầu
        toggleFields();

        // Lắng nghe sự kiện thay đổi trên dropdown Evidence Type
        evidenceType.addEventListener("change", toggleFields);
    });
</script>

