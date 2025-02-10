<div class="modal fade" id="accept-claim-modal" tabindex="-1"
     aria-labelledby="accept-claim-modal-label"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('app.dispute.acceptClaim', $dispute->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="accept-claim-modal-label">Accept Claim</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="accept-claim-note" class="form-label">Notes <span class="text-danger">*</span></label>
                        <textarea id="accept-claim-note" name="note" class="form-control" rows="3"
                                  maxlength="2000" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="accept-claim-reason" class="form-label">Accept Claim Reason (Optional)</label>
                        <select id="accept-claim-reason" name="accept_claim_reason" class="form-select">
                            <option value="" selected>-- Select Reason --</option>
                            <option value="DID_NOT_SHIP_ITEM">Did Not Ship Item</option>
                            <option value="TOO_TIME_CONSUMING">Too Time Consuming</option>
                            <option value="LOST_IN_MAIL">Lost in Mail</option>
                            <option value="NOT_ABLE_TO_WIN">Not Able to Win</option>
                            <option value="COMPANY_POLICY">Company Policy</option>
                            <option value="REASON_NOT_SET">Reason Not Set</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Invoice ID (Optional)</label>
                        <input type="text" name="invoice_id" maxlength="127" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="accept-claim-type" class="form-label">Accept Claim Type <span class="text-danger">*</span></label>
                        <select id="accept-claim-type" name="accept_claim_type" class="form-select" required>
                            <option value="REFUND" selected>Refund</option>
                            <option value="REFUND_WITH_RETURN">Refund with Return</option>
                            <option value="PARTIAL_REFUND">Partial Refund</option>
{{--                            <option value="REFUND_WITH_RETURN_SHIPMENT_LABEL" >Refund with Return Shipment Label</option>--}}
                        </select>
                    </div>

                    <div id="refund-amount-section" class="mb-3" style="display: none;">
                        <label class="form-label">Refund Amount</label>
                        <div class="input-group">
                            <select id="refund-currency" name="currency_code" class="form-select" required>
                                <option value="USD" selected>USD - United States dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="RUB">RUB - Russian ruble</option>
                                <option value="SGD">SGD - Singapore dollar</option>
                                <option value="AUD">AUD - Australian dollar</option>
                            </select>
                            <input type="number" maxlength="32" min="0.01" step="0.01" id="refund-value" name="value" class="form-control" placeholder="Amount">
                        </div>
                    </div>

                    <div id="return-shipping-section" class="mb-3" style="display: none;">
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
        const modal = document.getElementById("accept-claim-modal");
        const claimTypeSelect = modal.querySelector("select[name='accept_claim_type']");
        const refundAmountSection = modal.querySelector("#refund-amount-section");
        const returnShippingSection = modal.querySelector("#return-shipping-section");
        const valueInput          = modal.querySelector("input[name='value']");
        const currencySelect       = modal.querySelector("select[name='currency_code']");
        const addressInput          = modal.querySelector("input[name='address']");
        const countryCodeInput          = modal.querySelector("input[name='country_code']")

        function updateFormFields() {
            const selectedClaimType = claimTypeSelect.value;

            refundAmountSection.style.display = "none";
            returnShippingSection.style.display = "none";
            countryCodeInput.removeAttribute("required");
            addressInput.removeAttribute("required");
            valueInput.removeAttribute("required");
            currencySelect.removeAttribute("required");

            // "Partial Refund"
            if (selectedClaimType === "PARTIAL_REFUND") {
                refundAmountSection.style.display = "block";
                valueInput.setAttribute("required", "required");
                currencySelect.setAttribute("required", "required");
            }

            // "Refund With Return"
            if (selectedClaimType === "REFUND_WITH_RETURN" || selectedClaimType === "REFUND_WITH_RETURN_SHIPMENT_LABEL") {
                returnShippingSection.style.display = "block";
                addressInput.setAttribute("required", "required");
                countryCodeInput.setAttribute("required", "required");
            }
        }

        // Cập nhật form khi thay đổi loại Accept Claim
        claimTypeSelect.addEventListener("change", updateFormFields);

        // Gọi khi trang load lần đầu
        updateFormFields();
    });
</script>
