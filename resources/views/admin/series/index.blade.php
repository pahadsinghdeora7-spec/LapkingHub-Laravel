<x-layouts.admin title="Series | LapkingHub" page-title="Laptop Series Management">
    <div class="card shadow-sm mb-4"><div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-3"><input class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search series or manufacturer"></div>
            <div class="col-md-2"><select class="form-select" name="manufacturer_id"><option value="">All manufacturers</option>@foreach($manufacturers as $manufacturer)<option value="{{ $manufacturer->id }}" @selected(($filters['manufacturer_id'] ?? '') === $manufacturer->id)>{{ $manufacturer->name }}</option>@endforeach</select></div>
            <div class="col-md-2"><select class="form-select" name="status"><option value="">All statuses</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
            <div class="col-md-2"><select class="form-select" name="trashed"><option value="">Active</option><option value="with" @selected(($filters['trashed'] ?? '') === 'with')>With trashed</option><option value="only" @selected(($filters['trashed'] ?? '') === 'only')>Only trashed</option></select></div>
            <div class="col-md-2"><select class="form-select" name="sort"><option value="created_at" @selected(($filters['sort'] ?? '') === 'created_at')>Created</option><option value="name" @selected(($filters['sort'] ?? '') === 'name')>Name</option><option value="status" @selected(($filters['sort'] ?? '') === 'status')>Status</option><option value="updated_at" @selected(($filters['sort'] ?? '') === 'updated_at')>Updated</option></select></div>
            <div class="col-md-1"><select class="form-select" name="direction"><option value="desc" @selected(($filters['direction'] ?? '') === 'desc')>Desc</option><option value="asc" @selected(($filters['direction'] ?? '') === 'asc')>Asc</option></select></div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Filter</button></div>
        </form>
    </div></div>

    <div class="card shadow-sm"><div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Series</h5><a class="btn btn-primary" href="{{ route('admin.series.create') }}">Add Series</a></div>
        <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Series</th><th>Manufacturer</th><th>Status</th><th>Updated</th><th class="text-end">Actions</th></tr></thead><tbody>
            @forelse($series as $item)
                <tr class="{{ $item->trashed() ? 'table-warning' : '' }}"><td><div class="fw-semibold">{{ $item->name }}</div><small class="text-secondary">{{ $item->slug }}</small></td><td>{{ $item->manufacturer?->name ?: '—' }}</td><td><span class="badge text-bg-{{ $item->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($item->status) }}</span></td><td>{{ $item->updated_at?->diffForHumans() }}</td><td class="text-end">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.series.show', $item) }}">View</a>
                    @if(! $item->trashed())<a class="btn btn-sm btn-outline-primary" href="{{ route('admin.series.edit', $item) }}">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.series.destroy', $item) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>@else<form class="d-inline" method="POST" action="{{ route('admin.series.restore', $item) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-success">Restore</button></form><form class="d-inline" method="POST" action="{{ route('admin.series.force-delete', $item) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete Forever</button></form>@endif
                </td></tr>
            @empty
                <tr><td class="text-center text-secondary py-4" colspan="5">No series found.</td></tr>
            @endforelse
        </tbody></table></div><div class="card-footer">{{ $series->links() }}</div></div>
</x-layouts.admin>
