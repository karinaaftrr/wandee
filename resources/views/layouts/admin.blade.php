<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Wandee')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/styles.css', 'resources/js/script.js'])

    @stack('styles')
</head>
<body class="admin-page">

<div class="admin-wrap">
    @include('partials.admin_sidebar')

    <main class="admin-main">
        @yield('content')
    </main>
</div>

@include('partials.logout_modal')

@stack('scripts')
</body>
</html>
