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
                <div class="card-header d-flex flex-row justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">Role's permission List</h4>
                        <p class="text-muted mb-0">
                            This is a list of all roles' permission in the system.
                        </p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#instruction">
                            Instruction
                        </button>

                        <div class="modal fade bd-example-modal-lg" id="instruction" tabindex="-1" role="dialog" aria-labelledby="instructionLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title m-0" id="instructionLabel">Instruction</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div><!--end modal-header-->
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12 align-self-center">
                                                <p>
                                                    First, if no roles have been added to the permission table yet, click the <strong>Create</strong> button and select the role you want to add.
                                                    If the role already exists and you want to modify it, move on to the next step.
                                                </p>
                                                <p>
                                                    Role permissions follow a role hierarchy: this means a parent role will automatically inherit all the permissions of its child roles.
                                                </p>
                                                <p>
                                                    After adding a role, click the yellow <strong>Edit</strong> button. You will be redirected to a page where you can assign permissions to that role.
                                                    Toggle on the permissions you want to assign, and then click <strong>Submit</strong>.
                                                    (If a toggle is enabled but disabled, that means it is inherited from a child role and cannot be manually edited.)
                                                </p>
                                                <p>
                                                    Below is the current role hierarchy. You can click the <strong>Manage</strong> button to go to the role hierarchy management page,
                                                    where you can add, edit, or delete relationships between roles.
                                                </p>
                                            </div><!--end col-->
                                        </div><!--end row-->

                                    </div><!--end modal-body-->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-soft-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                    </div><!--end modal-footer-->
                                </div><!--end modal-content-->
                            </div><!--end modal-dialog-->
                        </div><!--end modal-->
                    </div>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <div class="col-lg-6">
                            <h4 class="card-title mb-4">Role Hierarchy</h4>
                        </div>
                        <div class="col-lg-6 d-flex justify-content-end max-h-2">
                            <button type="button" class="btn btn-outline-primary btn-sm">Manage Role Hierarchy</button>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12">
                            @foreach($roleHierarchies as $key => $value)
                                {!!
                                     $role = match ($key) {
                                                User::ROLE_USER => '<span class="badge badge-soft-secondary">'.User::ROLES[$key].'</span>',
                                                User::ROLE_ACCOUNTANT => '<span class="badge badge-soft-success">'.User::ROLES[$key].'</span>',
                                                User::ROLE_SUPPORT => '<span class="badge badge-soft-danger" style="background-color: #e0cffc !important; color: #6610f2 !important;">'.User::ROLES[$key].'</span>',
                                                User::ROLE_SELLER => '<span class="badge badge-soft-info" style="background-color: #ffe5d0 !important; color: #fd7e14 !important;">'.User::ROLES[$key].'</span>',
                                                default => '<span class="badge badge-soft-primary">'.User::ROLES[$key].'</span>',
                                            };

                                     echo '<span class="ms-1 me-1">:</span>';
                                !!}

                                @foreach ($value as $hierarchy)
                                    @if ($loop->first)
                                        @continue
                                    @endif

                                    {!! match ($hierarchy) {
                                        User::ROLE_USER => '<span class="badge badge-soft-secondary">'.User::ROLES[$hierarchy].'</span>',
                                        User::ROLE_ACCOUNTANT => '<span class="badge badge-soft-success">'.User::ROLES[$hierarchy].'</span>',
                                        User::ROLE_SUPPORT => '<span class="badge badge-soft-danger" style="background-color: #e0cffc !important; color: #6610f2 !important;">'.User::ROLES[$hierarchy].'</span>',
                                        User::ROLE_SELLER => '<span class="badge badge-soft-info" style="background-color: #ffe5d0 !important; color: #fd7e14 !important;">'.User::ROLES[$hierarchy].'</span>',
                                        default => '<span class="badge badge-soft-primary">'.User::ROLES[$hierarchy].'</span>',
                                    } !!}
                                @endforeach

                                {!! '<br></br>' !!}
                            @endforeach
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
