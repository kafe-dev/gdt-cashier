<div class="modal fade" id="acknowledge-returned-dispute-modal" tabindex="-1"
     aria-labelledby="acknowledge-returned-dispute-modal-label"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('app.dispute.acknowledgeReturned', $dispute->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="acknowledge-returned-dispute-modal-label">Acknowledge Returned
                        Item
                        <h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                </div><!--end modal-header-->
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <label for="note" class="form-label">Notes (optional)</label>
                            <textarea id="acknowledge-returned-note" name="note" class="form-control" rows="3"
                                      maxlength="2000"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="item_status" class="form-label">Status</label>
                            <select id="acknowledge-returned-item_status" name="item_status" class="form-select"
                                    required>
                                <option value="NORMAL" selected>Item Received Normally</option>
                                <option value="ISSUE">Has Issues</option>
                            </select>
                        </div>
                        <div id="acknowledge-returned-evidence-section">
                            <div class="mb-3">
                                <label for="evidence_type" class="form-label">Evidence Type <span
                                        class="text-danger">*</span></label>
                                <select id="acknowledge-returned-evidence_type" name="evidence_type"
                                        class="form-select" required>
                                    <option value="PROOF_OF_DAMAGE">Proof of Damage</option>
                                    <option value="THIRDPARTY_PROOF_FOR_DAMAGE_OR_SIGNIFICANT_DIFFERENCE">
                                        Third-Party Proof for Damage or Significant Difference
                                    </option>
                                    <option value="DECLARATION">Declaration</option>
                                    <option value="PROOF_OF_MISSING_ITEMS">Proof of Missing Items</option>
                                    <option value="PROOF_OF_EMPTY_PACKAGE_OR_DIFFERENT_ITEM">
                                        Proof of Empty Package or Different Item
                                    </option>
                                    <option value="PROOF_OF_ITEM_NOT_RECEIVED">Proof of Item Not Received</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="documents" class="form-label">Upload Evidence Documents <span
                                        class="text-danger">*</span></label>
                                <input type="file" id="acknowledge-returned-documents" name="documents[]"
                                       class="form-control" multiple required accept="image/*,application/pdf" >
                            </div>
                        </div>
                    </div><!--end row-->
                </div><!--end modal-body-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                </div><!--end modal-footer-->
            </form>
        </div><!--end modal-content-->
    </div><!--end modal-dialog-->
</div>
<script>
    // Acknowledge returned item
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("acknowledge-returned-dispute-modal");
        const itemStatusSelect = modal.querySelector("select[name='item_status']");
        const evidenceSection = modal.querySelector("#acknowledge-returned-evidence-section");
        const evidenceSelect = modal.querySelector("select[name='evidence_type']");
        const fileInput = modal.querySelector("input[name='documents[]']");
        function updateFormFields() {
            const selectedStatus = itemStatusSelect.value;
            evidenceSection.style.display = "none";
            evidenceSelect.removeAttribute("required");
            fileInput.removeAttribute("required");
            // Has Issues
            if (selectedStatus === "ISSUE") {
                evidenceSection.style.display = "block";
                evidenceSelect.setAttribute("required", "required");
                fileInput.setAttribute("required", "required");
            }
        }
        // Cập nhật khi thay đổi Item Status
        itemStatusSelect.addEventListener("change", updateFormFields);
        // Gọi khi trang load lần đầu
        updateFormFields();
    });
</script>
