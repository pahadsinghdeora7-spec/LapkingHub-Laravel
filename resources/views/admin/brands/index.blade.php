<x-layouts.admin title="Brands | LapkingHub" page-title="Brand Management">
    <div class="card shadow-sm mb-4"><div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-4"><input class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search brands"></div>
            <div class="col-md-2"><select class="form-select" name="status"><option value="">All statuses</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
            <div class="col-md-2"><input class="form-control" name="country" value="{{ $filters['country'] ?? '' }}" placeholder="Country"></div>
            <div class="col-md-2"><select class="form-select" name="trashed"><option value="">Active</option><option value="with" @selected(($filters['trashed'] ?? '') === 'with')>With trashed</option><option value="only" @selected(($filters['trashed'] ?? '') === 'only')>Only trashed</option></select></div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Filter</button></div>
        </form>
    </div></div>

    <div class="card shadow-sm"><div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Brands</h5><a class="btn btn-primary" href="{{ route('admin.brands.create') }}">Add Brand</a></div>
        <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Name</th><th>Status</th><th>Country</th><th>Website</th><th>Updated</th><th class="text-end">Actions</th></tr></thead><tbody>
            @forelse($brands as $brand)
                <tr class="{{ $brand->trashed() ? 'table-warning' : '' }}"><td><div class="fw-semibold">{{ $brand->name }}</div><small class="text-secondary">{{ $brand->slug }}</small></td><td><span class="badge text-bg-{{ $brand->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($brand->status) }}</span></td><td>{{ $brand->country ?: '—' }}</td><td>@if($brand->website)<a href="{{ $brand->website }}" target="_blank" rel="noopener">Visit</a>@else — @endif</td><td>{{ $brand->updated_at?->diffForHumans() }}</td><td class="text-end">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.brands.show', $brand) }}">View</a>
                    @if(! $brand->trashed())<a class="btn btn-sm btn-outline-primary" href="{{ route('admin.brands.edit', $brand) }}">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.brands.destroy', $brand) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>@else<form class="d-inline" method="POST" action="{{ route('admin.brands.restore', $brand) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-success">Restore</button></form><form class="d-inline" method="POST" action="{{ route('admin.brands.force-delete', $brand) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete Forever</button></form>@endif
                </td></tr>
            @empty
                <tr><td class="text-center text-secondary py-4" colspan="6">No brands found.</td></tr>
            @endforelse
        </tbody></table></div><div class="card-footer">{{ $brands->links() }}</div></div>
</x-layouts.admin>
