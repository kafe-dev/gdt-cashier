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
                        <input type="text" class="form-control" id="dispute_code" name="dispute_code" value="{{$dispute->dispute_id}}" required readonly placeholder="Dispute Code">
                    </div>
                    <div class="mb-3">
                        <label for="carrier_name" class="form-label">Carrier Name</label>
                        <input type="text" class="form-control" id="carrier_name" name="carrier_name" value="" required placeholder="Enter carrier name">
                    </div>

                    <div class="mb-3">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" class="form-control" id="tracking_number" name="tracking_number" value="" required placeholder="Enter tracking number">
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
