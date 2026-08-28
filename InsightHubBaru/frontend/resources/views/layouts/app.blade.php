<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Pro - Marketing Dashboard</title>
    <!-- Phosphor Icons -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/style.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/input.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/laporan.css') }}?v={{ time() }}">
    @stack('css')
</head>
<body>

    <!-- Top Navigation Bar -->
    @include('layouts.navbar')

    <!-- ═══════════════════════════════════════
         MAIN CONTENT
    ════════════════════════════════════════ -->
    <main class="main-content">
        @yield('content')
        
        <!-- Global Footer -->
        @include('layouts.footer')
    </main>

    @stack('scripts')
</body>
</html>
