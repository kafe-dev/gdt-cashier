<form action="{{ route('app.paygate.store') }}" method="POST">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter name" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="url">Url <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" placeholder="Enter url" value="{{ old('url') }}" required>
            @error('url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <small id="passwordHelp" class="form-text text-muted">Enter your paypalme link, e.g: <code>https://www.paypal.com/paypalme/username</code></small>
            <br>
            <small id="passwordHelp" class="form-text text-muted">If you dont have this, enter <code>https://www.paypal.com/</code> instead</small>
        </div>

        <div class="mb-3">
            <label class="form-label" for="api-data">Api data <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('api_data') is-invalid @enderror" id="api-data" name="api_data" placeholder="Enter api data" value="{{ old('api_data') }}" required>
            @error('api_data')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <small id="passwordHelp" class="form-text text-muted">Enter your API data, e.g: <code>{"client_key": "your_api_key", "secret_key": "your_secret_key"}</code></small>
        </div>

        <div class="mb-3">
            <label class="form-label" for="vps-data">Vps data <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('vps_data') is-invalid @enderror" id="vps-data" name="vps_data" placeholder="Enter vps data" value="{{ old('vps_data') }}" required>
            @error('vps_data')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <small id="passwordHelp" class="form-text text-muted">Enter your VPS data, e.g: <code>{"ips": "vps_ip_address", "username": "vps_username", "password": "vps_password"}</code></small>
        </div>

        <div class="mb-3">
            <label class="form-label" for="type">Type</label>
            <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" required>
                <option value="" disabled selected>Select type</option>
                <option value="0" {{ old('type') == '0' ? 'selected' : '' }}>Paypal</option>
                <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Stripe</option>
            </select>
            @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="mode">Mode</label>
            <select class="form-select @error('mode') is-invalid @enderror" name="mode" id="mode" required>
                <option value="" disabled selected>Select mode</option>
                <option value="0" {{ old('mode') == '0' ? 'selected' : '' }}>Sandbox</option>
                <option value="1" {{ old('mode') == '1' ? 'selected' : '' }}>Live</option>
            </select>
            @error('mode')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        {{ \App\Utils\ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
    </div>
</form>
