<form action="{{ isset($user) ? route('app.user.update', $user->id) : route('app.user.store') }}" method="POST">
    @csrf
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username"
                   value="{{ old('username', $user->username ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address"
                   value="{{ old('email', $user->email ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
            <input type="password" minlength="8" class="form-control" id="password" name="password"
                   value="" @if(!isset($user)) required placeholder="Enter password" @else  placeholder="********************************" @endif>

            @if(isset($user))
                <small id="passwordHelp" class="form-text text-muted">If not entered, the password will not change.</small>
            @endif

            <small id="passwordHelp" class="form-text text-muted">Never share your password with anyone else.</small>
        </div>

{{--        <div class="mb-3">--}}
{{--            <label class="form-label" for="role">Permission</label>--}}
{{--            <select class="form-select" id="role" name="role">--}}
{{--                <option value="0" {{ isset($user) ? (old('role', $user->role ?? '') == 0 ? 'selected' : '' ) : 'selected'}}>User</option>--}}
{{--                @if(\App\Models\User::ROLES[Auth::user()->role] == 'Admin')--}}
{{--                    <option value="5" {{ old('role', $user->role ?? '') == 5 ? 'selected' : '' }}>Sub Admin</option>--}}
{{--                @endif--}}
{{--                <option value="2" {{ old('role', $user->role ?? '') == 2 ? 'selected' : '' }}>Accountant</option>--}}
{{--                <option value="3" {{ old('role', $user->role ?? '') == 3 ? 'selected' : '' }}>Support</option>--}}
{{--                <option value="4" {{ old('role', $user->role ?? '') == 4 ? 'selected' : '' }}>Seller</option>--}}
{{--            </select>--}}
{{--        </div>--}}

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        {{ \App\Utils\ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
    </div>
</form>
