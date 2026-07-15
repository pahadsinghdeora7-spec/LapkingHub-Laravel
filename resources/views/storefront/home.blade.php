<x-layouts.storefront title="LapkingHub | B2B Laptop Parts Ecommerce">
    <section class="bg-white border-bottom py-5">
        <div class="container py-lg-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge text-bg-primary-subtle text-primary border border-primary-subtle rounded-pill mb-3">Public Storefront</span>
                    <h1 class="display-5 fw-bold mb-3">LapkingHub B2B laptop parts marketplace</h1>
                    <p class="lead text-secondary mb-4">Source batteries, adapters, keyboards, displays, and compatible laptop spares from a dedicated ecommerce catalog.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('storefront.products.index') }}" class="btn btn-primary btn-lg">Browse products</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Business login</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4">
                        <h2 class="h5 fw-bold">Catalog at a glance</h2>
                        <div class="row g-3 mt-2 text-center">
                            <div class="col-4"><div class="p-3 bg-light rounded-3"><div class="h4 mb-0">{{ $featuredProducts->count() }}</div><small>Featured</small></div></div>
                            <div class="col-4"><div class="p-3 bg-light rounded-3"><div class="h4 mb-0">{{ $categories->count() }}</div><small>Categories</small></div></div>
                            <div class="col-4"><div class="p-3 bg-light rounded-3"><div class="h4 mb-0">{{ $brands->count() }}</div><small>Brands</small></div></div>
                        </div>
                    </div></div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h3 mb-0">Featured products</h2>
            <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-primary btn-sm">View all</a>
        </div>
        <div class="row g-4">
            @forelse($featuredProducts as $product)
                <div class="col-sm-6 col-lg-3">
                    @include('storefront.products._card', ['product' => $product])
                </div>
            @empty
                <div class="col-12"><div class="alert alert-info mb-0">The storefront is ready. Active products will appear here when available.</div></div>
            @endforelse
        </div>
    </section>
</x-layouts.storefront>
