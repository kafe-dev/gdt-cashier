<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="{{ config('app.name') }}">
    <meta name="description" content="{{ config('app.name') }}">

    <title>@yield('title')</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/mappers/app.js')
    @endif

    @yield('stylesheet')
</head>
<body>
<div id="x-wrapper">
    @yield('body')
</div>

@yield('javascripts')

<script>
    window.addEventListener('DOMContentLoaded', () => {
        console.log('Application loaded');
    });
</script>
</body>
</html>
