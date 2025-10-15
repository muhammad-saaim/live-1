<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

    {{-- bootstrapcss --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="font-sans antialiased min-h-screen flex flex-col" style="font-family: 'Fredoka', sans-serif;">
<div class="flex-grow bg-gray-100 dark:bg-gray-900 flex flex-col">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main class="flex-grow">

        {{-- ✅ Alert from Middleware --}}
        @if (session('alert'))
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                <div id="alertBox" class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <div>{{ session('alert') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    const alertBox = document.getElementById('alertBox');
                    if (alertBox) alertBox.style.display = 'none';
                }, 5000);
            </script>
        @endif

        {{-- ✅ Success & Error Messages --}}
        @if (session('success'))
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            </div>
        @endif

        {{ $slot }}
    </main>
</div>

<!-- Footer Sticks to the Bottom -->
@include('layouts.footer')
</body>
</html>
