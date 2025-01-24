<form action="{{ isset($user) ? route('app.user.update', $user->id) : route('app.user.store') }}" method="POST" >
    @csrf
    @isset($user)
        @method('PUT')
    @endisset

    @if (session('flash_error'))
        <div class="alert alert-danger">
            {{ session('flash_error') }}
        </div>
    @endif
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="{{ old('username', $user->username ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" value="{{ old('email', $user->email ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" value="{{ old('password') }}" @if(!isset($user)) required @endif>
            <small id="passwordHelp" class="form-text text-muted">Never share your password with anyone else.</small>
        </div>

        <div class="mb-3">
            <label class="form-label" for="role">Permission</label>
            <select class="form-select" id="role" name="role">
                <option value="" disabled {{ old('role', $user->role ?? '') == null ? 'selected' : '' }}>Select permission</option>
                <option value="0" {{ old('role', $user->role ?? '') == 0 ? 'selected' : '' }}>User</option>
                <option value="1" {{ old('role', $user->role ?? '') == 1 ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        {{ \App\Utils\ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
    </div>
</form>
