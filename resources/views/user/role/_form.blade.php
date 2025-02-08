<form action="{{ route('app.user.roleManage.update', $user->id) }}" method="POST">
    @csrf
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="username" value="{{ old('username', $user->username ?? '') }}" required disabled>
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" value="{{ old('email', $user->email ?? '') }}" required disabled>
        </div>

        <div class="mb-3">
            <label class="form-label" for="role">Permission</label>
            <select class="form-select" id="role" name="role">
                <option value="0" {{ old('role', $user->role ?? '') == 0 ? 'selected' : '' }}>User</option>
                <option value="5" {{ old('role', $user->role ?? '') == 5 ? 'selected' : '' }}>Sub Admin</option>
                <option value="2" {{ old('role', $user->role ?? '') == 2 ? 'selected' : '' }}>Accountant</option>
                <option value="3" {{ old('role', $user->role ?? '') == 3 ? 'selected' : '' }}>Support</option>
                <option value="4" {{ old('role', $user->role ?? '') == 4 ? 'selected' : '' }}>Seller</option>
            </select>
        </div>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        {{ \App\Utils\ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
    </div>
</form>
