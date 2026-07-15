@props(['title' => 'LapkingHub B2B Ecommerce'])

@php
    $navigationCategories = \App\Models\Category::query()
        ->with(['children' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name')])
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->take(8)
        ->get(['id', 'name', 'slug', 'parent_id', 'is_active', 'sort_order']);
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="storefront-shell bg-body-tertiary min-vh-100 d-flex flex-column">
<header class="storefront-header sticky-top bg-body shadow-sm">
    <div class="top-strip bg-dark text-white py-2 d-none d-lg-block">
        <div class="container d-flex justify-content-between align-items-center small">
            <span>India's trusted B2B laptop parts procurement platform</span>
            <span class="d-flex gap-3"><span>Bulk pricing</span><span>GST invoices</span><span>Railway-ready storefront</span></span>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg bg-body border-bottom">
        <div class="container gap-3">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-primary me-lg-2" href="{{ route('storefront.home') }}">
                <span class="brand-mark">LH</span>
                <span>LapkingHub</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#storefrontNav" aria-controls="storefrontNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <form method="GET" action="{{ route('storefront.products.index') }}" class="storefront-search flex-grow-1 order-3 order-lg-2 w-100 w-lg-auto mt-3 mt-lg-0">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-body-tertiary border-end-0"><i class="bi bi-search"></i></span>
                    <input name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Search by product, SKU, OEM or part number" aria-label="Search products">
                    <button class="btn btn-primary px-4" type="submit">Search</button>
                </div>
            </form>

            <div class="collapse navbar-collapse order-2 order-lg-3 flex-grow-0" id="storefrontNav">
                <ul class="navbar-nav align-items-lg-center gap-lg-2 ms-lg-3">
                    <li class="nav-item dropdown position-static">
                        <button class="nav-link dropdown-toggle btn btn-link" data-bs-toggle="dropdown" aria-expanded="false">Categories</button>
                        <div class="dropdown-menu mega-menu border-0 shadow-lg p-4 w-100 mt-0">
                            <div class="container px-0">
                                <div class="row g-4">
                                    @forelse($navigationCategories as $category)
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <a class="fw-bold text-decoration-none text-body" href="{{ route('storefront.products.index', ['category' => $category->id]) }}">{{ $category->name }}</a>
                                            <div class="mt-2 d-grid gap-1">
                                                @foreach($category->children->take(4) as $child)
                                                    <a class="small text-secondary text-decoration-none" href="{{ route('storefront.products.index', ['category' => $child->id]) }}">{{ $child->name }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12"><div class="empty-state p-4 text-center">Categories will appear here once active records are available.</div></div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('storefront.products.index') }}">Products</a></li>
                    @guest
                        <li class="nav-item"><a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="btn btn-primary btn-sm" href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="nav-item"><a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="flex-grow-1">
    {{ $slot }}
</main>

<footer class="storefront-footer bg-dark text-white pt-5 mt-auto">
    <div class="container">
        <div class="newsletter-card rounded-4 p-4 p-lg-5 mb-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-7"><h2 class="h3 fw-bold mb-2">Get procurement alerts and part updates</h2><p class="mb-0 text-white-50">Subscribe for new arrivals, bulk deals, and inventory updates.</p></div>
                <div class="col-lg-5"><form class="input-group input-group-lg"><input type="email" class="form-control" placeholder="business@email.com" aria-label="Email"><button class="btn btn-warning" type="button">Subscribe</button></form></div>
            </div>
        </div>
        <div class="row g-4 pb-4">
            <div class="col-md-4"><h3 class="h5 fw-bold">LapkingHub</h3><p class="text-white-50 mb-0">Professional B2B ecommerce for laptop batteries, keyboards, screens, adapters, and compatible spare parts.</p></div>
            <div class="col-6 col-md-2"><h4 class="h6 fw-bold">Catalog</h4><a class="footer-link" href="{{ route('storefront.products.index') }}">Products</a><a class="footer-link" href="{{ route('storefront.home') }}">Categories</a></div>
            <div class="col-6 col-md-2"><h4 class="h6 fw-bold">Account</h4><a class="footer-link" href="{{ route('login') }}">Login</a><a class="footer-link" href="{{ route('register') }}">Register</a></div>
            <div class="col-md-4"><h4 class="h6 fw-bold">B2B promise</h4><div class="d-flex flex-wrap gap-2"><span class="badge text-bg-secondary">GST-ready</span><span class="badge text-bg-secondary">Bulk orders</span><span class="badge text-bg-secondary">OEM lookup</span></div></div>
        </div>
        <div class="border-top border-secondary py-3 small text-white-50">© {{ date('Y') }} LapkingHub B2B Ecommerce. All rights reserved.</div>
    </div>
</footer>
</body>
</html>
