@extends('_layouts.main')

@push('page')
    Update Role Hierarchy ID: {{ $hierarchy->id }}
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('update-role-hierarchy') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Role Hierarchy ID: {{ $hierarchy->id }}</h4>
                    <p class="text-muted mb-0">Fill out the form below to update a role hierarchy.</p>
                    <p class="text-muted mb-0">Field with the (<span class="text-danger">*</span>) is required.</p>
                </div>

                <form action="{{ route('app.user.role.hierarchy.update', $hierarchy->id) }}" method="POST">
                    @csrf
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label" for="parent_role">Role</label>
                            <select class="form-select" id="parent_role" name="parent_role">
                                @for($i = 0,$iMax = count(\App\Models\User::ROLES); $i < $iMax; $i++)
                                    <option value="{{ $i }}" {{ old('parent_role', $hierarchy->parent_role ?? '') === $i ? 'selected' : '' }}>{{ \App\Models\User::ROLES[$i] }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="child_role">Child Role</label>
                            <select class="form-select" id="child_role" name="child_role">
                                @for($i = 0,$iMax = count(\App\Models\User::ROLES); $i < $iMax; $i++)
                                    <option value="{{ $i }}" {{ old('child_role', $hierarchy->child_role ?? '') === $i ? 'selected' : '' }}>{{ \App\Models\User::ROLES[$i] }}</option>
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
