<x-layouts.admin title="Manufacturers | LapkingHub" page-title="Manufacturer Management">
    <div class="card shadow-sm mb-4"><div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-4"><input class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search manufacturers"></div>
            <div class="col-md-2"><select class="form-select" name="status"><option value="">All statuses</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
            <div class="col-md-2"><input class="form-control" name="country" value="{{ $filters['country'] ?? '' }}" placeholder="Country"></div>
            <div class="col-md-2"><select class="form-select" name="trashed"><option value="">Active</option><option value="with" @selected(($filters['trashed'] ?? '') === 'with')>With trashed</option><option value="only" @selected(($filters['trashed'] ?? '') === 'only')>Only trashed</option></select></div>
            <div class="col-md-2"><select class="form-select" name="sort"><option value="created_at" @selected(($filters['sort'] ?? '') === 'created_at')>Created</option><option value="name" @selected(($filters['sort'] ?? '') === 'name')>Name</option><option value="status" @selected(($filters['sort'] ?? '') === 'status')>Status</option><option value="country" @selected(($filters['sort'] ?? '') === 'country')>Country</option><option value="updated_at" @selected(($filters['sort'] ?? '') === 'updated_at')>Updated</option></select></div>
            <div class="col-md-2"><select class="form-select" name="direction"><option value="desc" @selected(($filters['direction'] ?? '') === 'desc')>Descending</option><option value="asc" @selected(($filters['direction'] ?? '') === 'asc')>Ascending</option></select></div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Filter</button></div>
        </form>
    </div></div>

    <div class="card shadow-sm"><div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Manufacturers</h5><a class="btn btn-primary" href="{{ route('admin.manufacturers.create') }}">Add Manufacturer</a></div>
        <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Name</th><th>Status</th><th>Country</th><th>Updated</th><th class="text-end">Actions</th></tr></thead><tbody>
            @forelse($manufacturers as $manufacturer)
                <tr class="{{ $manufacturer->trashed() ? 'table-warning' : '' }}"><td><div class="fw-semibold">{{ $manufacturer->name }}</div><small class="text-secondary">{{ $manufacturer->slug }}</small></td><td><span class="badge text-bg-{{ $manufacturer->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($manufacturer->status) }}</span></td><td>{{ $manufacturer->country ?: '—' }}</td><td>{{ $manufacturer->updated_at?->diffForHumans() }}</td><td class="text-end">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.manufacturers.show', $manufacturer) }}">View</a>
                    @if(! $manufacturer->trashed())<a class="btn btn-sm btn-outline-primary" href="{{ route('admin.manufacturers.edit', $manufacturer) }}">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.manufacturers.destroy', $manufacturer) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>@else<form class="d-inline" method="POST" action="{{ route('admin.manufacturers.restore', $manufacturer) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-success">Restore</button></form><form class="d-inline" method="POST" action="{{ route('admin.manufacturers.force-delete', $manufacturer) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete Forever</button></form>@endif
                </td></tr>
            @empty
                <tr><td class="text-center text-secondary py-4" colspan="5">No manufacturers found.</td></tr>
            @endforelse
        </tbody></table></div><div class="card-footer">{{ $manufacturers->links() }}</div></div>
</x-layouts.admin>
