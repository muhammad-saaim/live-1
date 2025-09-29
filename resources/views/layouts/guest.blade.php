<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
  
    </head>
    <body class="font-sans antialiased" style="font-family: 'Fredoka', sans-serif;">
        <div class="flex flex-col min-h-screen bg-gray-100">
            <div class="flex flex-col justify-center items-center pt-5 sm:pt-2 sm:pb-3">
                <div>
                    <a href="/">
                        <x-application-logo class="w-40 h-40 fill-current text-gray-500" />
                    </a>
                </div>
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg bg-ml-color-lime">
                    <div class="p-2 space-y-4 md:space-y-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            @include('components.footer')
        </div>
    </body>
</html>
