<div class="modal fade" id="make-offer-dispute-modal" tabindex="-1" aria-labelledby="make-offer-dispute-modal-label"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('app.dispute.makeOffer', $dispute->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="make-offer-dispute-modal-label">Make offer to resolve
                        dispute</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">Offer Type <span class="text-danger">*</span></label>
                            <select id="offer_type" name="offer_type" class="form-select" required>
                                <option value="REFUND" selected>Refund</option>
                                <option value="REFUND_WITH_RETURN">Refund with Return</option>
                                <option value="REFUND_WITH_REPLACEMENT">Refund with Replacement</option>
                                <option value="REPLACEMENT_WITHOUT_REFUND">Replacement without Refund</option>
                            </select>
                        </div>

                        <div id="amount-section">
                            <div class="mb-3">
                                <label class="form-label">Amount <span class="text-danger">*</span></label>
                                <input type="number" name="amount" maxlength="32" min="0.01" step="0.01"
                                       class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Currency <span class="text-danger">*</span></label>
                                <select name="currency" class="form-select" required>
                                    <option value="USD" selected>USD - United States dollar</option>
                                    <option value="EUR">EUR - Euro</option>
                                    <option value="RUB">RUB - Russian ruble</option>
                                    <option value="SGD">SGD - Singapore dollar</option>
                                    <option value="AUD">AUD - Australian dollar</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note <span class="text-danger">*</span></label>
                            <textarea name="note" maxlength="2000" class="form-control" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Invoice ID (Optional)</label>
                            <input type="text" name="invoice_id" maxlength="127" class="form-control">
                        </div>
                        <div id="return-address-section">
                            <div class="mb-3">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" maxlength="300" class="form-control mb-2" placeholder="Address">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Country Code <span class="text-danger">*</span></label>
                                <input type="text" name="country_code" maxlength="2" class="form-control mb-2"
                                       placeholder="Country Code" pattern="^([A-Z]{2}|C2)$">
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
    // Make offer modal
    document.addEventListener("DOMContentLoaded", function() {
        const modal                = document.getElementById("make-offer-dispute-modal");
        const offerTypeSelect      = modal.querySelector("select[name='offer_type']");
        const amountSection        = modal.querySelector("#amount-section");
        const returnAddressSection = modal.querySelector("#return-address-section");
        const amountInput          = modal.querySelector("input[name='amount']");
        const currencySelect       = modal.querySelector("select[name='currency']")
        const addressInput         = modal.querySelector("input[name='address']");
        const countryCodeInput     = modal.querySelector("input[name='country_code']");

        function updateFormFields() {
            const selectedType = offerTypeSelect.value;

            amountSection.style.display        = "none";
            returnAddressSection.style.display = "none";
            amountInput.removeAttribute("required");
            currencySelect.removeAttribute("required");
            addressInput.removeAttribute("required");
            countryCodeInput.removeAttribute("required");

            // REFUND và REFUND_WITH_REPLACEMENT
            if(selectedType === "REFUND" || selectedType === "REFUND_WITH_REPLACEMENT" || selectedType === "REFUND_WITH_RETURN") {
                amountSection.style.display = "block";
                amountInput.setAttribute("required", "required");
                currencySelect.setAttribute("required", "required");
            }

            // REFUND_WITH_RETURN
            if(selectedType === "REFUND_WITH_RETURN") {
                returnAddressSection.style.display = "block";
                addressInput.setAttribute("required", "required");
                countryCodeInput.setAttribute("required", "required");
            }
        }

        // Cập nhật khi thay đổi Offer Type
        offerTypeSelect.addEventListener("change", updateFormFields);

        // Gọi khi trang load lần đầu
        updateFormFields();
    });
</script>
