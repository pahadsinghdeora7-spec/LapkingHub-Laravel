<x-layouts.storefront title="{{ $product->name }} | LapkingHub">
    <div class="container py-5">
        <a href="{{ route('storefront.products.index') }}" class="btn btn-link px-0 mb-3">← Back to products</a>
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm"><div class="card-body">
                    @if($product->primaryImage?->url)
                        <img src="{{ $product->primaryImage->url }}" class="img-fluid rounded" alt="{{ $product->name }}">
                    @else
                        <div class="ratio ratio-4x3 bg-body-tertiary rounded d-flex align-items-center justify-content-center text-secondary">LapkingHub</div>
                    @endif
                </div></div>
            </div>
            <div class="col-lg-7">
                <span class="badge text-bg-primary mb-2">{{ $product->category?->name ?? 'Laptop Parts' }}</span>
                <h1 class="fw-bold">{{ $product->name }}</h1>
                <p class="lead text-secondary">{{ $product->short_description }}</p>
                <div class="h3 text-primary fw-bold mb-3">₹{{ number_format((float) $product->price, 2) }}</div>
                <dl class="row">
                    <dt class="col-sm-3">Brand</dt><dd class="col-sm-9">{{ $product->brand?->name ?? '—' }}</dd>
                    <dt class="col-sm-3">SKU</dt><dd class="col-sm-9">{{ $product->sku }}</dd>
                    <dt class="col-sm-3">OEM Part</dt><dd class="col-sm-9">{{ $product->oem_part_number ?: '—' }}</dd>
                    <dt class="col-sm-3">Availability</dt><dd class="col-sm-9">{{ str($product->stock_status)->replace('_', ' ')->title() }}</dd>
                    <dt class="col-sm-3">MOQ</dt><dd class="col-sm-9">{{ $product->minimum_order_quantity ?? 1 }}</dd>
                </dl>
                <div class="border-top pt-3 text-secondary">{{ $product->description }}</div>
            </div>
        </div>
    </div>
</x-layouts.storefront>
