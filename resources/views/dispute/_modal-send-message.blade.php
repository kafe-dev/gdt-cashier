<div class="modal fade" id="send-message-dispute-modal" tabindex="-1" aria-labelledby="send-message-dispute-modal-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="send-message-dispute-modal-label">Send message about dispute to other party</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body">
                <div class="row">
                    <form action="{{ route('app.dispute.send-message') }}" method="POST">
                        @csrf
                        <input type="hidden" class="form-control" id="paygate_id" name="paygate_id" value="{{$paygate->id}}" required readonly>
                        <input type="hidden" class="form-control" id="dispute_id" name="dispute_id" value="{{$dispute->id}}" required readonly>

                        <div class="form-group">
                            <label for="dispute_id">Dispute Code:</label>
                            <input type="text" class="form-control" id="dispute_code" name="dispute_code" value="{{$dispute_arr['dispute_id']??'N/A'}}" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="message">Message:</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div><!--end row-->
            </div><!--end modal-body-->
        </div><!--end modal-content-->
    </div><!--end modal-dialog-->
</div>
