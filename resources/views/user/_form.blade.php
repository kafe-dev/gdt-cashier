<form method="post">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="username" placeholder="Enter username" value="{{ $user->username ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" placeholder="Enter email address" value="{{ $user->email ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password" aria-describedby="passwordHelp" placeholder="Enter password" value="{{ $user->password ?? '' }}" required>
            <small id="passwordHelp" class="form-text text-muted">Never share your password with anyone else.</small>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Permission</label>
            <select class="form-select" aria-label="Default select example">
                <option selected="">Select permission</option>
                <option value="0">User</option>
                <option value="1">Admin</option>
            </select>
        </div>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        {{ \App\Utils\ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
    </div>
</form>
