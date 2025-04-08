@extends('_layouts.main')

@push('page')
    Create User Permission Role
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('create-permission') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New User Permission Role</h4>
                    <p class="text-muted mb-0">Fill out the form below to create a new user permission role.</p>
                    <p class="text-muted mb-0">Field with the (<span class="text-danger">*</span>) is required.</p>
                </div>

                <form action="{{ route('app.user.permission.store') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label" for="role">Role</label>
                            <select class="form-select" id="role" name="role">
                                @for($i = 0,$iMax = count(\App\Models\User::ROLES); $i < $iMax; $i++)
                                    @if(!\App\Models\Permission::where('role', $i)->exists())
                                        <option value="{{ $i }}" {{ old('role', $user->role ?? '') === $i ? 'selected' : '' }}>{{ \App\Models\User::ROLES[$i] }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        {{ \App\Utils\ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
                    </div>
                </form>

            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
