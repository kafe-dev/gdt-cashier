<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="{{ config('app.name') }}">
    <meta name="description" content="{{ config('app.name') }}">

    <title>@yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('theme/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/assets/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/assets/css/app.min.css') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/mappers/app.js')
    @endif

    @stack('stylesheets')
</head>
<body class="@stack('body-class')">

@yield('body')

<script src="{{ asset('theme/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('theme/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('theme/assets/js/waves.js') }}"></script>
<script src="{{ asset('theme/assets/js/feather.min.js') }}"></script>
<script src="{{ asset('theme/assets/js/simplebar.min.js') }}"></script>

@stack('javascripts')

<script>
    window.addEventListener('DOMContentLoaded', () => {
        console.log('Application loaded.');
    });
</script>
</body>
</html>
