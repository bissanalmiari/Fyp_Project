<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
         <!-- link to poppins font -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Rammetto+One&display=swap"
            rel="stylesheet">
        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('css/common.css') }}">
        
        @stack('styles')
        
    </head>
    <body class="font-sans antialiased">

        {{-- NAVBAR --}}
        @include('partials.nav')

        <div class="min-h-screen bg-[#F6F4FE]">

            {{-- Page Heading --}}
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Page Content --}}
            <main>
                {{ $slot }}
            </main>

        </div>

        {{-- FOOTER --}}
        @include('partials.footer')
        @include('partials.scroll-top')
        <script src="{{ asset('js/common.js') }}"></script>
        
        @stack('scripts')
    </body>
</html>
