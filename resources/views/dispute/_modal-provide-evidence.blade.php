<div class="modal fade" id="provide-evidence-dispute-modal" tabindex="-1"
     aria-labelledby="provide-evidence-dispute-modal-label"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('app.dispute.provideEvidence', $dispute->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="provide-evidence-dispute-modal-label">Provide Evidence</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="evidence-type" class="form-label">Evidence Type <span class="text-danger">*</span></label>
                        <select id="evidence-type" name="evidence_type" class="form-select" required>
                            <option value="PROOF_OF_DELIVERY" selected>Proof of Delivery</option>
                            <option value="PROOF_OF_REFUND">Proof of Refund</option>
                            <option value="OTHER">Other</option>
                        </select>
                    </div>

                    <div id="proof-of-delivery-section" class="mb-3">
                        <label class="form-label">Tracking Number <span class="text-danger">*</span></label>
                        <input type="text" name="tracking_number" maxlength="50" class="form-control" required>
                        <label class="form-label mt-2">Carrier Name <span class="text-danger">*</span></label>
                        <input type="text" name="carrier_name" maxlength="100" class="form-control" required>
                    </div>

                    <div id="proof-of-refund-section" class="mb-3" style="display: none;">
                        <label class="form-label">Refund ID <span class="text-danger">*</span></label>
                        <input type="text" name="refund_id" maxlength="50" class="form-control">
                    </div>

                    <div id="other-evidence-section" class="mb-3" style="display: none;">
                        <label class="form-label">Notes <span class="text-danger">*</span></label>
                        <textarea name="notes" class="form-control" rows="3" maxlength="2000"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Attach Document (Optional)</label>
                        <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.png,.jpeg">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("provide-evidence-modal");
        const evidenceTypeSelect = modal.querySelector("#evidence-type");
        const proofOfDeliverySection = modal.querySelector("#proof-of-delivery-section");
        const proofOfRefundSection = modal.querySelector("#proof-of-refund-section");
        const otherEvidenceSection = modal.querySelector("#other-evidence-section");
        const trackingInput = modal.querySelector("input[name='tracking_number']");
        const carrierInput = modal.querySelector("input[name='carrier_name']");
        const refundInput = modal.querySelector("input[name='refund_id']");
        const notesInput = modal.querySelector("textarea[name='notes']");

        function updateFormFields() {
            const selectedType = evidenceTypeSelect.value;

            proofOfDeliverySection.style.display = "none";
            proofOfRefundSection.style.display = "none";
            otherEvidenceSection.style.display = "none";
            trackingInput.removeAttribute("required");
            carrierInput.removeAttribute("required");
            refundInput.removeAttribute("required");
            notesInput.removeAttribute("required");

            if (selectedType === "PROOF_OF_DELIVERY") {
                proofOfDeliverySection.style.display = "block";
                trackingInput.setAttribute("required", "required");
                carrierInput.setAttribute("required", "required");
            }

            if (selectedType === "PROOF_OF_REFUND") {
                proofOfRefundSection.style.display = "block";
                refundInput.setAttribute("required", "required");
            }

            if (selectedType === "OTHER") {
                otherEvidenceSection.style.display = "block";
                notesInput.setAttribute("required", "required");
            }
        }

        evidenceTypeSelect.addEventListener("change", updateFormFields);
        updateFormFields();
    });
</script>
