<x-layouts.admin title="Product Images | LapkingHub" page-title="{{ $product->name }} Gallery">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-secondary btn-sm">Back to Product</a>
            <span class="badge text-bg-info ms-2">{{ $product->images()->count() }} image(s)</span>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header fw-semibold">Upload Images</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.images.store', $product) }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-md-5"><label class="form-label">Images</label><input class="form-control" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp" multiple required></div>
                <div class="col-md-3"><label class="form-label">Alt Text</label><input class="form-control" name="alt_text" value="{{ old('alt_text') }}"></div>
                <div class="col-md-3"><label class="form-label">Title</label><input class="form-control" name="title" value="{{ old('title') }}"></div>
                <div class="col-md-1 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_primary" value="1" id="is_primary"><label class="form-check-label" for="is_primary">Primary</label></div></div>
                <div class="col-12"><div class="border border-dashed rounded p-3 text-secondary bg-light">Drag & drop upload placeholder: use the file picker now; enhanced JavaScript drop-zone can be attached here.</div></div>
                <div class="col-12"><button class="btn btn-primary">Upload</button></div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <form class="row g-2 align-items-center">
                <div class="col-md-5"><input class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search title, alt text, file name"></div>
                <div class="col-md-3"><select class="form-select" name="trashed"><option value="">Active</option><option value="with" @selected(($filters['trashed'] ?? '') === 'with')>With trashed</option><option value="only" @selected(($filters['trashed'] ?? '') === 'only')>Only trashed</option></select></div>
                <div class="col-md-2"><button class="btn btn-outline-primary w-100">Search</button></div>
            </form>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.images.sort', $product) }}">
                @csrf @method('PATCH')
                <div class="row g-3">
                    @forelse($images as $image)
                        <div class="col-md-4 col-xl-3">
                            <div class="card h-100 {{ $image->trashed() ? 'opacity-75' : '' }}">
                                <img src="{{ $image->trashed() ? '#' : $image->url }}" class="card-img-top bg-light" style="height:160px;object-fit:cover" alt="{{ $image->alt_text }}" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22240%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f3f5%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 fill=%22%236c757d%22%3EImage%3C/text%3E%3C/svg%3E'">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2"><strong>{{ $image->title ?: $image->image_name }}</strong>@if($image->is_primary)<span class="badge text-bg-success">Primary</span>@endif</div>
                                    <p class="small text-secondary mb-2">{{ $image->alt_text ?: 'No alt text' }}</p>
                                    <label class="form-label small">Sort Order</label>
                                    <input type="hidden" name="image_ids[]" value="{{ $image->id }}">
                                    <input class="form-control form-control-sm" value="{{ $image->sort_order }}" disabled>
                                </div>
                                <div class="card-footer d-flex flex-wrap gap-2">
                                    @unless($image->trashed())
                                        <button form="primary-{{ $image->id }}" class="btn btn-sm btn-outline-success" @disabled($image->is_primary)>Set Primary</button>
                                        <button form="delete-{{ $image->id }}" class="btn btn-sm btn-outline-danger">Delete</button>
                                    @else
                                        <button form="restore-{{ $image->id }}" class="btn btn-sm btn-outline-primary">Restore</button>
                                        <button form="force-{{ $image->id }}" class="btn btn-sm btn-danger">Force Delete</button>
                                    @endunless
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-secondary py-5">No product images found.</div>
                    @endforelse
                </div>
                <div class="mt-3"><button class="btn btn-outline-primary">Save Current Order</button></div>
            </form>
            <div class="mt-3">{{ $images->links() }}</div>
        </div>
    </div>

    @foreach($images as $image)
        <form id="primary-{{ $image->id }}" method="POST" action="{{ route('admin.products.images.primary', [$product, $image]) }}" class="d-none">@csrf @method('PATCH')</form>
        <form id="delete-{{ $image->id }}" method="POST" action="{{ route('admin.products.images.destroy', [$product, $image]) }}" class="d-none">@csrf @method('DELETE')</form>
        <form id="restore-{{ $image->id }}" method="POST" action="{{ route('admin.products.images.restore', [$product, $image->id]) }}" class="d-none">@csrf @method('PATCH')</form>
        <form id="force-{{ $image->id }}" method="POST" action="{{ route('admin.products.images.force-delete', [$product, $image->id]) }}" class="d-none">@csrf @method('DELETE')</form>
    @endforeach
</x-layouts.admin>
