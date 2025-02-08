@php
    use App\Models\User;
@endphp

@extends('_layouts.main')

@push('page')
    Manage Roles' Permission
@endpush

@push('breadcrumbs')
        {{ Breadcrumbs::render('manage-permission') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Role's permission List</h4>
                        <p class="text-muted mb-0">
                            This is a list of all roles' permission in the system.
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
                <div class="card-footer">
                    <h4 class="card-title mb-4">Role Hierarchy</h4>

                    <div class="row mb-2">
                        <div class="col-12">
                            <span class="badge badge-soft-primary">{{ User::ROLES[User::ROLE_ADMIN] }}</span>
                            <span class="ms-1 me-1">:</span>
                            <span class="badge badge-soft-primary">{{ User::ROLES[User::ROLE_SUB_ADMIN] }}</span>
                            <span class="badge badge-soft-danger">{{ User::ROLES[User::ROLE_SUPPORT] }}</span>
                            <span class="badge badge-soft-success">{{ User::ROLES[User::ROLE_ACCOUNTANT] }}</span>
                            <span class="badge badge-soft-info">{{ User::ROLES[User::ROLE_SELLER] }}</span>
                            <span class="badge badge-soft-secondary">{{ User::ROLES[User::ROLE_USER] }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <span class="badge badge-soft-primary">{{ User::ROLES[User::ROLE_SUB_ADMIN] }}</span>
                            <span class="ms-1 me-1">:</span>
                            <span class="badge badge-soft-danger">{{ User::ROLES[User::ROLE_SUPPORT] }}</span>
                            <span class="badge badge-soft-success">{{ User::ROLES[User::ROLE_ACCOUNTANT] }}</span>
                            <span class="badge badge-soft-info">{{ User::ROLES[User::ROLE_SELLER] }}</span>
                            <span class="badge badge-soft-secondary">{{ User::ROLES[User::ROLE_USER] }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <span class="badge badge-soft-primary">{{ User::ROLES[User::ROLE_SUPPORT] }}</span>
                            <span class="ms-1 me-1">:</span>
                            <span class="badge badge-soft-secondary">{{ User::ROLES[User::ROLE_USER] }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <span class="badge badge-soft-primary">{{ User::ROLES[User::ROLE_ACCOUNTANT] }}</span>
                            <span class="ms-1 me-1">:</span>
                            <span class="badge badge-soft-secondary">{{ User::ROLES[User::ROLE_USER] }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <span class="badge badge-soft-primary">{{ User::ROLES[User::ROLE_SELLER] }}</span>
                            <span class="ms-1 me-1">:</span>
                            <span class="badge badge-soft-secondary">{{ User::ROLES[User::ROLE_USER] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
