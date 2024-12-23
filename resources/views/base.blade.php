<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title') | {{ config('app.name') }}</title>

    {{-- Theme style --}}
    <link rel="stylesheet" href="{{ asset('theme/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/app.min.css') }}">
    {{-- Theme style --}}

    {{-- Preload modules --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/mappers/app.js')
    @endif
    {{-- Preload modules --}}

    {{-- Custom stylesheet --}}
    @yield('stylesheet')
    {{-- Custom stylesheet --}}
</head>
<body>
<div id="x-wrapper">

    {{-- Main section --}}
    @yield('body')
    {{-- Main section --}}

</div>

{{-- Theme scripts --}}
<script src="{{ asset('theme/js/vendor.min.js') }}"></script>
<script src="{{ asset('theme/js/app.min.js') }}"></script>
{{-- Theme scripts --}}

{{-- Custom javascript --}}
@yield('javascript')
{{-- Custom javascript --}}
</body>
</html>
