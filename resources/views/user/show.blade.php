@extends('_layouts.main')

@push('page')
    View User ID: {{ $user->id }}
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('show-user', $user) }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User ID: {{ $user->id }}</h4>
                    <p class="text-muted mb-0">This table below is showing the detail information of user ID: {{ $user->id }}</p>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">ID:</span>
                            </div>
                            <span>{{ $user->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Username:</span>
                            </div>
                            <span>{{ $user->username }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Email address:</span>
                            </div>
                            <span><a href="mailto:{{ $user->email }}" class="text-primary">{{ $user->email }}</a></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Password:</span>
                            </div>
                            <span class="badge badge-soft-secondary">Secure</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Permission:</span>
                            </div>
                            {!!
                                $status = match ($user->role) {
                                    $user::ROLE_USER => '<span class="badge badge-soft-secondary">'.$user::ROLES[$user->role].'</span>',
                                    default => '<span class="badge badge-soft-primary">'.$user::ROLES[$user->role].'</span>',
                                }
                            !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Registration IP:</span>
                            </div>
                            <span>{{ $user->registration_ip }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Status:</span>
                            </div>
                            {!!
                                $status = match ($user->status) {
                                    $user::STATUS_INACTIVE => '<span class="badge badge-soft-secondary">'.$user::STATUSES[$user->status].'</span>',
                                    $user::STATUS_BLOCKED => '<span class="badge badge-soft-danger">'.$user::STATUSES[$user->status].'</span>',
                                    default => '<span class="badge badge-soft-success">'.$user::STATUSES[$user->status].'</span>',
                                }
                            !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Email Verified At:</span>
                            </div>
                            {!! ! empty($user->email_verified_at) ? '<span class="x-has-time-converter">'.$user->email_verified_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Last Login At:</span>
                            </div>
                            {!! ! empty($user->last_login_at) ? '<span class="x-has-time-converter">'.$user->last_login_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Blocked At:</span>
                            </div>
                            {!! ! empty($user->blocked_at) ? '<span class="x-has-time-converter">'.$user->blocked_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Created At:</span>
                            </div>
                            {!! ! empty($user->created_at) ? '<span class="x-has-time-converter">'.$user->created_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Updated At:</span>
                            </div>
                            {!! ! empty($user->updated_at) ? '<span class="x-has-time-converter">'.$user->updated_at->format(config('app.date_format')).'</span>' : '-' !!}
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    {{ \App\Utils\ActionWidget::renderUpdateBtn(route('app.user.update', ['id' => $user->id]), 'Update', 'btn btn-warning text-white') }}
                    {{ \App\Utils\ActionWidget::renderDeleteBtn($user->id, route('app.user.delete', ['id' => $user->id]), 'Delete', 'btn btn-danger') }}
                    {{ \App\Utils\ActionWidget::renderGoBackBtn('Go Back', 'btn btn-primary') }}
                </div>
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
