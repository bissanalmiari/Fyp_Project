<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UniPath') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('style')
</head>
<body class="font-sans antialiased">
    @include('partials.nav')

    @yield('content')

    @include('partials.footer')
    @include('partials.scroll-top')
    <script src="{{ asset('js/common.js') }}"></script>
</body>
</html>
