<x-layouts.storefront title="Products | LapkingHub">
    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div><h1 class="h2 fw-bold mb-1">Product catalog</h1><p class="text-secondary mb-0">Browse public LapkingHub B2B ecommerce products.</p></div>
        </div>
        <form method="GET" action="{{ route('storefront.products.index') }}" class="card border-0 shadow-sm mb-4"><div class="card-body row g-3">
            <div class="col-md-4"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search product, SKU, OEM part"></div>
            <div class="col-md-3"><select name="category" class="form-select"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category') === $category->id)>{{ $category->name }}</option>@endforeach</select></div>
            <div class="col-md-3"><select name="brand" class="form-select"><option value="">All brands</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(request('brand') === $brand->id)>{{ $brand->name }}</option>@endforeach</select></div>
            <div class="col-md-2 d-grid"><button class="btn btn-primary">Filter</button></div>
        </div></form>
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-sm-6 col-lg-4 col-xl-3">@include('storefront.products._card', ['product' => $product])</div>
            @empty
                <div class="col-12"><div class="alert alert-info">No active products match your filters.</div></div>
            @endforelse
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</x-layouts.storefront>
