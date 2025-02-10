@php use App\Models\User; @endphp
@php use App\Utils\ActionWidget; @endphp
<form action="{{ route('app.tracking.addTracking', $orderTracking->id) }}" method="POST">
    @csrf
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label" for="status">Order Status</label>
            <select class="form-select" id="status" name="status">
                <option value="SHIPPED">SHIPPED</option>
                <option value="CANCELLED">CANCELLED</option>
                <option value="DELIVERED">DELIVERED</option>
                <option value="LOCAL_PICKUP">LOCAL_PICKUP</option>
                <option value="ON_HOLD">ON_HOLD</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" for="username">Tracking Number</label>
            <input type="text" class="form-control" id="trackingNumber" name="tracking_number"
                   placeholder="Enter tracking number"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="carrier">Carrier</label>
            <input type="text" class="form-control" id="carrier" name="courier_code" placeholder="Enter Carrier"
                   required>
        </div>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        {{ ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
    </div>

</form>
