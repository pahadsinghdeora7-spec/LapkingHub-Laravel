<div class="product-card card h-100 border-0 shadow-sm">
    <div class="ratio ratio-4x3 bg-body-tertiary rounded-top overflow-hidden">
        @if($product->primaryImage?->url)
            <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
        @else
            <div class="d-flex align-items-center justify-content-center text-secondary">LapkingHub</div>
        @endif
    </div>
    <div class="card-body d-flex flex-column">
        <div class="small text-secondary mb-1">{{ $product->brand?->name ?? 'LapkingHub' }} @if($product->sku) · {{ $product->sku }} @endif</div>
        <h3 class="h6 fw-bold lh-base"><a class="text-decoration-none text-dark" href="{{ route('storefront.products.show', $product) }}">{{ $product->name }}</a></h3>
        <p class="small text-secondary flex-grow-1">{{ str($product->short_description ?: $product->description)->limit(90) }}</p>
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold text-primary">₹{{ number_format((float) $product->price, 2) }}</span>
            <span class="badge text-bg-{{ $product->stock_status === 'in_stock' ? 'success' : 'secondary' }}">{{ str($product->stock_status)->replace('_', ' ')->title() }}</span>
        </div>
    </div>
</div>
