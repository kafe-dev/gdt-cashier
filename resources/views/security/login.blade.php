@extends('_layouts.blank')

@push('page')
    Login
@endpush

@section('content')
    <form class="form-horizontal auth-form" method="post">
        @csrf
        <div class="form-group mb-2">
            <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required>
            </div>
        </div>

        <div class="form-group mb-2">
            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
            </div>
        </div>

        <div class="form-group row my-3">
            <div class="col-sm-6">
                <div class="custom-control custom-switch switch-success">
                    <input type="checkbox" class="custom-control-input" name="remember" id="customSwitchSuccess">
                    <label class="form-label text-muted" for="customSwitchSuccess">Remember me</label>
                </div>
            </div>
        </div>

        <div class="form-group mb-0 row">
            <div class="col-12">
                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Login <i class="fas fa-sign-in-alt ms-1"></i></button>
            </div>
        </div>
    </form>
@endsection
