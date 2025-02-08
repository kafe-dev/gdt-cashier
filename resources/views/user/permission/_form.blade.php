<form action="{{ route('app.user.permission.update', $permission->id) }}" method="POST">
    @csrf
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label" for="role">Role</label>
            <input type="text" class="form-control" id="role" value="{{ \App\Models\User::ROLES[$permission->role] }}" disabled>
        </div>

        <h4 class="card-title">List route</h4>
        <div class="mb-3">
            @foreach($routeNames as $index => $route)
                <div class="form-check form-switch form-switch-info">
                    <input class="form-check-input" type="checkbox"
                           {{ in_array($route, $hierarchyAllowed) ? '' : 'name="switch'.$index.'"' }}
                           id="customSwitchInfo" value="{{ $route }}"
                        {{ in_array($route, $routeAllowed) ? 'checked' : '' }}
                        {{ in_array($route, $hierarchyAllowed) ? 'checked disabled' : '' }}>
                    <label class="form-check-label" for="customSwitchInfo">{{ $route }}</label>
                </div>
            @endforeach
        </div>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        {{ \App\Utils\ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
    </div>
</form>
