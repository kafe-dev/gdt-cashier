@php
    $evidence_type = [
        'PROOF_OF_FULFILLMENT',
        'PROOF_OF_REFUND',
        'OTHER',
    ];

    $carrier_options = \App\Models\Carrier::all()->pluck('name', 'code')->toArray();

@endphp
<style>
    /* Tăng chiều cao input file */
    .custom-file-input {
        height: 60px;
        padding: 15px;
        cursor: pointer;
        border: 2px dashed #007bff;
        text-align: center;
        background-color: #f8f9fa;
    }

    /* Hiệu ứng hover */
    .custom-file-input:hover {
        background-color: #e9ecef;
    }

    /* Ẩn input file mặc định */
    .file-input {
        display: none;
    }

    /* Kiểu dáng danh sách file */
    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
    }

    .file-item i {
        font-size: 20px;
        margin-right: 10px;
    }

    .file-name {
        flex-grow: 1;
        font-weight: 500;
    }

    .btn-delete {
        border: none;
        background: none;
        color: red;
        font-size: 18px;
        cursor: pointer;
    }

    .btn-delete:hover {
        color: darkred;
    }
</style>
<!-- Select2 CSS -->
<div class="modal fade" id="provide-evidence-modal" tabindex="-1"
     aria-labelledby="provide-evidence-modal-label"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('app.dispute.provideEvidence', $dispute->id) }}" method="POST" enctype="multipart/form-data">
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
                    <div id="fulfillment_fields" class="border p-3">

                        <div class="mb-3">
                            <label for="carrier_name" class="form-label">Carrier Name (<span class="text-danger">*</span>)</label>
                            <select class="form-control select2" id="carrier_name" name="carrier_name[]">
                                <option value="">Select Carrier</option>
                                @foreach($carrier_options as $code => $name)
                                    <option value="{{$code}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tracking_number" class="form-label">Tracking Number(<span class="text-danger">*</span>)</label>
                            <input type="text" class="form-control" id="tracking_number" name="tracking_number[]"
                                   value="" placeholder="Enter tracking number">
                        </div>

                        <div class="mb-3">
                            <label for="tracking_number" class="form-label">Add Tracking Link</label>
                            <input type="text" class="form-control" id="tracking_url" name="tracking_url[]"
                                   value="" placeholder="Enter tracking link">
                        </div>
                    </div>

                    <p id="add_more_fulfillment_fields" class="text-primary mt-3">
                        <a href="javascript:void(0)"><i class="fas fa-plus"></i> Add more Shipments</a>
                    </p>

                    <!-- Refund ID (Chỉ hiển thị khi chọn PROOF_OF_REFUND) -->
                    <div id="refund_fields">
                        <div class="mb-3">
                            <label for="refund_id" class="form-label">Refund ID(<span class="text-danger">*</span>)</label>
                            <input type="text" class="form-control" id="refund_id" name="refund_id"
                                   value="" placeholder="Enter refund id">
                        </div>
                    </div>
                    <div id="file_upload_fields">
                        <label class="form-label">Upload Files (<span class="text-danger">*</span>)</label>

                        <!-- Ô upload tùy chỉnh -->
                        <div class="custom-file-input" id="customFileInput">
                            <i class="fas fa-upload fa-2x"></i>
                            <p class="mt-2 text-muted"><i>Click to upload files</i></p>
                            <input type="file" class="file-input" id="evidence_file" name="evidence_file[]" multiple>
                        </div>

                        <!-- Danh sách hiển thị file đã chọn -->
                        <ul id="file_list" class="list-group mt-3"></ul>
                    </div>

                    <div id="notes_fields">
                        <div class="mb-3">
                            <label for="note" class="form-label">Notes(<span class="text-danger">*</span>)</label>
                            <textarea class="form-control" id="note" name="note" rows="5" placeholder="Enter notes"></textarea>
                        </div>
                    </div>

                </div><!--end modal-body-->

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm px-5"><i class="fas fa-paper-plane"></i> Submit
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>

                </div><!--end modal-footer-->
            </form>

        </div><!--end modal-content-->
    </div><!--end modal-dialog-->
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let evidenceType                = document.getElementById("evidence_type");
        let fulfillmentFields           = document.getElementById("fulfillment_fields");
        let refundFields                = document.getElementById("refund_fields");
        let notesFields                 = document.getElementById("notes_fields");
        let fileUploads                 = document.getElementById("file_upload_fields");
        let btnAddMoreFulfillmentFields = document.getElementById("add_more_fulfillment_fields");
        let carrierName                 = document.getElementById("carrier_name");

        function toggleFields() {
            let selectedValue = evidenceType.value;

            // Ẩn tất cả các trường trước khi kiểm tra điều kiện
            fulfillmentFields.style.display           = "none";
            refundFields.style.display                = "none";
            notesFields.style.display                 = "none";
            fileUploads.style.display                 = "none";
            btnAddMoreFulfillmentFields.style.display = "none";

            if(selectedValue === "PROOF_OF_FULFILLMENT") {
                fulfillmentFields.style.display = "block"; // Hiển thị Carrier Name & Tracking Number
                carrierName.setAttribute("required", "required");
                //notesFields.style.display       = "block"; // Hiển thị Notes
                btnAddMoreFulfillmentFields.style.display = "block";
            } else if(selectedValue === "PROOF_OF_REFUND") {
                refundFields.style.display = "block"; // Hiển thị Refund ID
                notesFields.style.display  = "block"; // Hiển thị Notes
            } else if(selectedValue === "OTHER") {
                notesFields.style.display = "block"; // Hiển thị Notes
                fileUploads.style.display = "block"; // Hiển thị File Path
            }

        }

        // Ẩn tất cả các trường khi tải trang lần đầu
        toggleFields();

        // Lắng nghe sự kiện thay đổi trên dropdown Evidence Type
        evidenceType.addEventListener("change", toggleFields);

        $("#add_more_fulfillment_fields").click(function() {
            let newFulfillment = $("#fulfillment_fields").first().clone(); // Clone div fulfillment_fields
            newFulfillment.find("input").val(""); // Xóa giá trị trong input
            newFulfillment.insertAfter("#fulfillment_fields:last"); // Chèn sau cùng fulfillment_fields
        });

        document.getElementById("customFileInput").addEventListener("click", function() {
            document.getElementById("evidence_file").click();
        });

        document.getElementById("evidence_file").addEventListener("change", function() {
            let fileList       = document.getElementById("file_list");
            fileList.innerHTML = ""; // Xóa danh sách cũ

            Array.from(this.files).forEach((file, index) => {
                let fileItem       = document.createElement("div");
                fileItem.className = "file-item";

                // Lấy icon theo loại file
                let fileType = file.name.split('.').pop().toLowerCase();
                let fileIcon = '<i class="fas fa-file-alt text-primary"></i>';
                if(['jpg', 'jpeg', 'png'].includes(fileType)) {
                    fileIcon = '<i class="fas fa-file-image text-success"></i>';
                } else if(['pdf'].includes(fileType)) {
                    fileIcon = '<i class="fas fa-file-pdf text-danger"></i>';
                } else if(['doc', 'docx'].includes(fileType)) {
                    fileIcon = '<i class="fas fa-file-word text-info"></i>';
                }
                fileItem.innerHTML = `${fileIcon}<span class="file-name">${file.name}</span>`;

                fileList.appendChild(fileItem);
            });
        });


    });
</script>


