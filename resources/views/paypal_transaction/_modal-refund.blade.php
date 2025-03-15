<div class="modal fade" id="refund-payment-modal" tabindex="-1" aria-labelledby="refund-payment-modal-label"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="refund-form" action="#"
                  method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="refund-payment-modal-label">Refund Captured Payment</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">Refund Type <span class="text-danger">*</span></label>
                            <select id="refund_type" name="refund_type" class="form-select" required>
                                <option value="FULL" selected>Full Refund</option>
                                <option value="PARTIAL">Partial Refund</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gross Amount</label>
                            <input type="text" id="gross_amount_display" class="form-control" readonly>
                        </div>

                        <div id="amount-section" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Refund Amount <span class="text-danger">*</span></label>
                                <input type="number" name="amount" maxlength="32" min="0.01" step="0.01"
                                       class="form-control">
                            </div>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note to Payer</label>
                            <textarea name="note_to_payer" maxlength="255" class="form-control"></textarea>
                            <small class="text-muted">Appears in payer's transaction history and emails.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Invoice ID</label>
                            <input type="text" name="invoice_id" maxlength="127" class="form-control">
                            <small class="text-muted">External invoice ID for this order.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Custom ID</label>
                            <input type="text" name="custom_id" maxlength="127" class="form-control">
                            <small class="text-muted">External ID to reconcile transactions.</small>
                        </div>

                    </div><!--end row-->
                </div><!--end modal-body-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Process Refund</button>
                </div><!--end modal-footer-->
            </form>
        </div><!--end modal-content-->
    </div><!--end modal-dialog-->
</div>
<script>
    // Refund payment modal
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('refund-payment-modal')
        const refundForm = document.getElementById('refund-form')
        const refundTypeSelect = modal.querySelector('select[name=\'refund_type\']')
        const amountSection = modal.querySelector('#amount-section')
        const amountInput = modal.querySelector('input[name=\'amount\']')
        const grossAmountDisplay = modal.querySelector('#gross_amount_display')

        // Handle capture ID from the trigger element
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget
            const captureId = button.getAttribute('data-id')
            const grossAmount = button.getAttribute('data-gross')
            const transactionCurrency = button.getAttribute('data-currency')

            console.log(captureId)

            // Update the form action with the capture ID
            if (captureId) {
                // Set the form action to the route with the dynamic ID
                refundForm.setAttribute('action',
                    `{{ route('app.paypal-transaction.refundPayment', ':id') }}`.replace(':id', captureId))

                // You can also store the ID in a hidden input if needed
                let hiddenInput = refundForm.querySelector('input[name="capture_id"]')
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input')
                    hiddenInput.type = 'hidden'
                    hiddenInput.name = 'capture_id'
                    refundForm.appendChild(hiddenInput)
                }
                hiddenInput.value = captureId
            }

            if (grossAmount) {
                grossAmountDisplay.value = grossAmount + ' ' + transactionCurrency
                amountInput.setAttribute('max', grossAmount)
            } else {
                grossAmountDisplay.value = ''
            }

        })

        function updateFormFields () {
            const selectedType = refundTypeSelect.value

            if (selectedType === 'PARTIAL') {
                amountSection.style.display = 'block'
                amountInput.setAttribute('required', 'required')

            } else {
                amountSection.style.display = 'none'
                amountInput.removeAttribute('required')
               
            }
        }

        // Update when refund type changes
        refundTypeSelect.addEventListener('change', updateFormFields)

        // Call when page first loads
        updateFormFields()
    })
</script>
