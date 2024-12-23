@extends('_layouts.blank')

@section('title', 'Login')

@section('form')
    <div class="text-center mb-4 mt-3">
        <a href="{{ config('app.url') }}">
            <span><img src="{{ Vite::asset('resources/assets/images/logo.png') }}" alt="app-logo" height="50"></span>
        </a>
        <h5 class="text-uppercase font-weight-bold font-24 text-primary">{{ config('app.name') }}</h5>
    </div>
    <form method="post" class="p-2">
        <div class="form-group">
            <label for="username">Username <span class="text-danger">*</span></label>
            <input class="form-control" type="text" id="username" placeholder="Enter your username" required>
        </div>
        <div class="form-group">
            <label for="password">Password <span class="text-danger">*</span></label>
            <input class="form-control" type="password" id="password" placeholder="Enter your password" required>
        </div>

        <div class="form-group mb-4 pb-3">
            <div class="custom-control custom-checkbox checkbox-primary">
                <input type="checkbox" class="custom-control-input" id="checkbox-signin">
                <label class="custom-control-label" for="checkbox-signin">Remember me</label>
            </div>
        </div>
        <div class="mb-3 text-center">
            <button class="btn btn-primary btn-block" type="submit">Login</button>
        </div>
    </form>
@endsection
