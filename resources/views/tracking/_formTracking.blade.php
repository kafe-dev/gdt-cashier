@php use App\Models\User; @endphp
@php use App\Utils\ActionWidget; @endphp
@php
    $carriers = [];
    if (file_exists(base_path() . '/app/Console/Commands/carrie.json')) {
        $carriers = file_get_contents(base_path() . '/app/Console/Commands/carrie.json');
        $carriers = json_decode($carriers, true, 512, JSON_THROW_ON_ERROR);
    }
@endphp
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
            <label class="form-label" for="trackingNumber">Tracking Number</label>
            <input type="text" class="form-control" id="trackingNumber" name="tracking_number"
                   placeholder="Enter tracking number"
                   required>
        </div>

        <div class="mb-3" id="carrier-div">
            <label class="form-label" for="carrier">Carrier</label>
            <select class="form-select" name="carrier" id="carrier">
                @foreach($carriers as $code => $name)
                    <option value="{{ $code }}" {{ old('carrier') === $code ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" for="other_carrier">Other carrier (Enter this if you choose "Other" option)</label>
            <input type="text" class="form-control" id="other_carrier" name="other_carrier"
                   placeholder="Enter other carrier">
        </div>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        {{ ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
    </div>

</form>
