<div class="modal fade" id="escalate-dispute-modal" tabindex="-1" aria-labelledby="escalate-dispute-modal-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('app.dispute.escalate') }}" method="POST">
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="escalate-dispute-modal-label">Escalate dispute to claim</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                <div class="modal-body">
                    <div class="row">
                        @csrf
                        <input type="hidden" class="form-control" id="paygate_id" name="paygate_id" value="{{$paygate->id}}" required readonly>
                        <input type="hidden" class="form-control" id="dispute_id" name="dispute_id" value="{{$dispute->id}}" required readonly>

                        <div class="form-group">
                            <label for="dispute_id">Dispute Code:</label>
                            <input type="text" class="form-control" id="dispute_code" name="dispute_code" value="{{$dispute_arr['dispute_id']??'N/A'}}" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="message">Note:</label>
                            <textarea class="form-control" id="note" name="note" rows="4" required></textarea>
                        </div>
                    </div><!--end row-->
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
