@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Brand Name</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $brand->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="slug">Slug</label>
        <input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $brand->slug) }}" placeholder="Auto-generated when empty">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="status">Status</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $brand->status ?? 'active') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="country">Country</label>
        <input class="form-control @error('country') is-invalid @enderror" id="country" name="country" maxlength="2" value="{{ old('country', $brand->country) }}" placeholder="US">
        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="logo">Logo</label>
        <input class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" type="file" accept="image/*">
        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="website">Website</label>
        <input class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $brand->website) }}" placeholder="https://example.com">
        @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="seo_title">Meta Title</label>
        <input class="form-control @error('seo_title') is-invalid @enderror" id="seo_title" name="seo_title" value="{{ old('seo_title', $brand->seo_title) }}">
        @error('seo_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $brand->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="seo_description">Meta Description</label>
        <textarea class="form-control @error('seo_description') is-invalid @enderror" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $brand->seo_description) }}</textarea>
        @error('seo_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="d-flex gap-2 mt-4">
    <button class="btn btn-primary" type="submit">Save Brand</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.brands.index') }}">Cancel</a>
</div>
