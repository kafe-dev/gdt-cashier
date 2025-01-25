<form action="{{ route('app.paygate.updated', $paygate->id) }}" method="POST">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter name" value="{{ old('name', $paygate->name) }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="url">Url <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" placeholder="Enter url" value="{{ old('url', $paygate->url) }}" required>
            @error('url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="api-data">Api data <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('api_data') is-invalid @enderror" id="api-data" name="api_data" placeholder="Enter api data" value="{{ old('api_data', $paygate->api_data) }}" required>
            @error('api_data')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="vps-data">Vps data <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('vps_data') is-invalid @enderror" id="vps-data" name="vps_data" placeholder="Enter vps data" value="{{ old('vps_data', $paygate->vps_data) }}" required>
            @error('vps_data')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="type">Type</label>
            <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" required>
                <option value="" disabled>Select type</option>
                <option value="0" {{ old('type', $paygate->type) == '0' ? 'selected' : '' }}>Paypal</option>
                <option value="1" {{ old('type', $paygate->type) == '1' ? 'selected' : '' }}>Stripe</option>
            </select>
            @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="mode">Mode</label>
            <select class="form-select @error('mode') is-invalid @enderror" name="mode" id="mode" required>
                <option value="" disabled>Select mode</option>
                <option value="0" {{ old('mode', $paygate->mode) == '0' ? 'selected' : '' }}>Sandbox</option>
                <option value="1" {{ old('mode', $paygate->mode) == '1' ? 'selected' : '' }}>Live</option>
            </select>
            @error('mode')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        {{ \App\Utils\ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
    </div>
</form>
