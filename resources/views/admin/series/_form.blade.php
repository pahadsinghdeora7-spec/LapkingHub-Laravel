@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="manufacturer_id">Manufacturer</label>
        <select class="form-select @error('manufacturer_id') is-invalid @enderror" id="manufacturer_id" name="manufacturer_id" required>
            <option value="">Select manufacturer</option>
            @foreach($manufacturers as $manufacturer)
                <option value="{{ $manufacturer->id }}" @selected(old('manufacturer_id', $series->manufacturer_id) === $manufacturer->id)>{{ $manufacturer->name }}</option>
            @endforeach
        </select>
        @error('manufacturer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="name">Series Name</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $series->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="slug">Slug</label>
        <input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $series->slug) }}" placeholder="Auto-generated when empty">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="status">Status</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $series->status ?? 'active') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="seo_title">SEO Title</label>
        <input class="form-control @error('seo_title') is-invalid @enderror" id="seo_title" name="seo_title" value="{{ old('seo_title', $series->seo_title) }}">
        @error('seo_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $series->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="seo_description">SEO Description</label>
        <textarea class="form-control @error('seo_description') is-invalid @enderror" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $series->seo_description) }}</textarea>
        @error('seo_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="d-flex gap-2 mt-4">
    <button class="btn btn-primary" type="submit">Save Series</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.series.index') }}">Cancel</a>
</div>
