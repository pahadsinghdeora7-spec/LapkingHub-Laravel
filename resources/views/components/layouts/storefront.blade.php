<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'LapkingHub B2B Ecommerce' }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light min-vh-100 d-flex flex-column">
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('storefront.home') }}">LapkingHub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#storefrontNav" aria-controls="storefrontNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="storefrontNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('storefront.products.index') }}">Products</a></li>
            </ul>
            <div class="d-flex gap-2">
                @guest
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Register</a>
                @else
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.dashboard') }}">Admin</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-danger btn-sm" type="submit">Logout</button></form>
                @endguest
            </div>
        </div>
    </div>
</nav>
<main class="flex-grow-1">
    {{ $slot }}
</main>
<footer class="bg-white border-top py-4 mt-auto"><div class="container small text-secondary">© {{ date('Y') }} LapkingHub B2B Ecommerce.</div></footer>
</body>
</html>
