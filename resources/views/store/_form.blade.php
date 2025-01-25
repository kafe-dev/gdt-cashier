<form action="{{ isset($store) ? route('app.store.update', $store->id) : route('app.store.store') }}" method="POST">
    @csrf
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label" for="owner">Owner <span class="text-danger">*</span></label>
            <select class="form-select" id="owner" name="user_id" required>
                <option value="" disabled @if(!isset($store)) selected @endif >Choose shop owner</option>
                @foreach($users as $user )
                    <option
                        value="{{ $user['id'] }}" {{ old('user_id', $store->user_id ?? '') == $user['id'] ? 'selected' : '' }}>
                        {{$user['username'] . " / " . $user['email']}}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" for="name">Store Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter the store name"
                   value="{{ old('name', $store->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="url">Url <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="url" name="url" placeholder="Enter the store URL"
                   value="{{ old('url', $store->url ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="api_data">Description</label>
            <textarea class="form-control" id="api_data" name="description" rows="5"
                      placeholder="Enter a description of the store.">{{ old('description', $store->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label" for="api_data">API Data <span class="text-danger">*</span></label>
            <textarea class="form-control" id="api_data" name="api_data" rows="5" required
                      placeholder="Enter API data store.">{{ old('api_data', $store->api_data ?? '') }}</textarea>
        </div>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        {{ \App\Utils\ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
    </div>
</form>
