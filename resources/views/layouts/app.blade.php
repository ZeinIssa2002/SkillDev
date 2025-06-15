<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SkillDev - @yield('title')</title>
    <link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @include('layouts.navbar')

    <main class="py-4">
        @yield('content')
    </main>

    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
