<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <link rel="icon" href="{{ asset('storage/profile_picture/favicon.ico') }}" type="image/x-icon"> -->
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Menambahkan Favicon untuk Seluruh Aplikasi -->
        <link rel="icon" href="{{ asset('bookmeet.png') }}" type="image/x-icon">
        <!-- <link rel="icon" href="{{ asset('bookmeet.png') }}" type="image/x-icon"> -->
        <!-- Favicon untuk berbagai ukuran jika diperlukan -->
        <link rel="icon" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" sizes="64x64" href="{{ asset('favicon-64x64.png') }}">

        <!-- Untuk aplikasi web (Apple, Android) -->
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
