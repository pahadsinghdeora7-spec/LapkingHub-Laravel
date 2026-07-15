<x-layouts.storefront title="LapkingHub | B2B Laptop Parts Ecommerce">
    <section class="hero-section position-relative overflow-hidden py-5">
        <div class="container py-lg-5 position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item active text-white-50" aria-current="page">Home</li></ol></nav>
                    <span class="badge rounded-pill text-bg-warning mb-3">Amazon Business-style laptop parts sourcing</span>
                    <h1 class="display-4 fw-bold text-white mb-3">Buy laptop parts faster with verified B2B catalog workflows.</h1>
                    <p class="lead text-white-50 mb-4">Search OEM numbers, compare compatible models, and source batteries, adapters, keyboards, displays, and spares from a production-ready Laravel storefront.</p>
                    <div class="d-flex flex-wrap gap-3"><a href="{{ route('storefront.products.index') }}" class="btn btn-warning btn-lg px-4">Browse catalog</a><a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">Create business account</a></div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-metric-card rounded-4 p-4 shadow-lg">
                        <h2 class="h5 fw-bold mb-4">Procurement snapshot</h2>
                        <div class="row g-3 text-center">
                            <div class="col-4"><div class="metric-tile"><div class="h3 mb-0">{{ $featuredProducts->count() }}</div><small>Featured</small></div></div>
                            <div class="col-4"><div class="metric-tile"><div class="h3 mb-0">{{ $categories->count() }}</div><small>Categories</small></div></div>
                            <div class="col-4"><div class="metric-tile"><div class="h3 mb-0">{{ $brands->count() }}</div><small>Brands</small></div></div>
                        </div>
                        <div class="loading-skeleton mt-4 rounded-pill"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="section-heading"><span class="eyebrow">Shop by category</span><h2>High-demand laptop spare categories</h2></div>
        <div class="row g-4">
            @forelse($categories as $category)
                <div class="col-6 col-lg-3"><a class="category-card card h-100 border-0 shadow-sm text-decoration-none" href="{{ route('storefront.products.index', ['category' => $category->id]) }}"><div class="card-body"><div class="category-icon mb-3">🧩</div><h3 class="h6 text-body fw-bold mb-1">{{ $category->name }}</h3><p class="small text-secondary mb-0">Explore compatible parts</p></div></a></div>
            @empty
                <div class="col-12"><div class="empty-state">No active categories yet. Add categories from the admin panel to populate this section.</div></div>
            @endforelse
        </div>
    </section>

    <section class="bg-body py-4 border-y"><div class="container"><div class="brand-slider d-flex gap-3 overflow-auto pb-2">@forelse($brands as $brand)<a class="brand-pill" href="{{ route('storefront.products.index', ['brand' => $brand->id]) }}">{{ $brand->name }}</a>@empty<span class="text-secondary">Active brands will appear here.</span>@endforelse</div></div></section>

    @foreach(['Featured products' => $featuredProducts, 'New products' => $newProducts, 'Best sellers' => $bestSellers] as $heading => $collection)
        <section class="container py-5">
            <div class="d-flex justify-content-between align-items-end gap-3 mb-4"><div class="section-heading mb-0"><span class="eyebrow">{{ $loop->iteration === 1 ? 'Recommended' : ($loop->iteration === 2 ? 'Fresh inventory' : 'Popular') }}</span><h2>{{ $heading }}</h2></div><a href="{{ route('storefront.products.index') }}" class="btn btn-outline-primary btn-sm">View all</a></div>
            <div class="row g-4">
                @forelse($collection as $product)<div class="col-sm-6 col-lg-3">@include('storefront.products._card', ['product' => $product])</div>@empty<div class="col-12"><div class="empty-state">Products will appear here when active inventory is available.</div></div>@endforelse
            </div>
        </section>
    @endforeach
</x-layouts.storefront>
