<x-layouts.admin title="Laptop Models | LapkingHub" page-title="Laptop Model Management">
    <div class="card shadow-sm mb-4"><div class="card-body"><form class="row g-3" method="GET">
        <div class="col-md-3"><input class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search model, series, manufacturer"></div>
        <div class="col-md-2"><select class="form-select" name="manufacturer_id"><option value="">All manufacturers</option>@foreach($manufacturers as $manufacturer)<option value="{{ $manufacturer->id }}" @selected(($filters['manufacturer_id'] ?? '') === $manufacturer->id)>{{ $manufacturer->name }}</option>@endforeach</select></div>
        <div class="col-md-2"><select class="form-select" name="series_id"><option value="">All series</option>@foreach($seriesOptions as $series)<option value="{{ $series->id }}" @selected(($filters['series_id'] ?? '') === $series->id)>{{ $series->name }}</option>@endforeach</select></div>
        <div class="col-md-2"><select class="form-select" name="status"><option value="">All statuses</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
        <div class="col-md-2"><select class="form-select" name="trashed"><option value="">Active</option><option value="with" @selected(($filters['trashed'] ?? '') === 'with')>With trashed</option><option value="only" @selected(($filters['trashed'] ?? '') === 'only')>Only trashed</option></select></div>
        <div class="col-md-1"><button class="btn btn-outline-primary w-100">Filter</button></div>
    </form></div></div>

    <div class="card shadow-sm"><div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Laptop Models</h5><a class="btn btn-primary" href="{{ route('admin.laptop-models.create') }}">Add Laptop Model</a></div>
        <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Model</th><th>Manufacturer</th><th>Series</th><th>Year</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
            @forelse($laptopModels as $item)
                <tr class="{{ $item->trashed() ? 'table-warning' : '' }}"><td><div class="fw-semibold">{{ $item->model_name }}</div><small class="text-secondary">{{ $item->model_number ?: $item->slug }}</small></td><td>{{ $item->manufacturer?->name ?: '—' }}</td><td>{{ $item->series?->name ?: '—' }}</td><td>{{ $item->release_year ?: '—' }}</td><td><span class="badge text-bg-{{ $item->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($item->status) }}</span></td><td class="text-end">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.laptop-models.show', $item) }}">View</a>
                    @if(! $item->trashed())<a class="btn btn-sm btn-outline-primary" href="{{ route('admin.laptop-models.edit', $item) }}">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.laptop-models.destroy', $item) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>@else<form class="d-inline" method="POST" action="{{ route('admin.laptop-models.restore', $item) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-success">Restore</button></form><form class="d-inline" method="POST" action="{{ route('admin.laptop-models.force-delete', $item) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete Forever</button></form>@endif
                </td></tr>
            @empty<tr><td class="text-center text-secondary py-4" colspan="6">No laptop models found.</td></tr>@endforelse
        </tbody></table></div><div class="card-footer">{{ $laptopModels->links() }}</div></div>
</x-layouts.admin>
