<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @yield('styles')
</head>

<body class="font-sans antialiased">

    <!-- Parallax Background -->
    <div class="parallax-wrapper">
        @for ($i = 0; $i < 10; $i++)
            <div class="box {{ $i % 2 === 0 ? 'box2' : '' }}"></div>
        @endfor
    </div>

    <!-- Foreground Content -->
    <div class="content-wrapper">
        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
