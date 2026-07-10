<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'LapkingHub') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-body-tertiary min-vh-100 d-flex flex-column">
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">LapkingHub</a>
        <div class="ms-auto d-flex gap-2">
            @guest
                <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Login</a>
                <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Register</a>
            @else
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">Logout</button>
                </form>
            @endguest
        </div>
    </div>
</nav>
<main class="flex-grow-1 d-flex align-items-center py-5">
    <div class="container">
        {{ $slot }}
    </div>
</main>
</body>
</html>
