<x-layouts.admin title="Categories | LapkingHub" page-title="Category Management">
    <div class="card shadow-sm mb-4"><div class="card-body"><form class="row g-3" method="GET">
        <div class="col-md-3"><input class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search categories"></div>
        <div class="col-md-2"><select class="form-select" name="status"><option value="">All statuses</option><option value="1" @selected(($filters['status'] ?? '') === '1')>Active</option><option value="0" @selected(($filters['status'] ?? '') === '0')>Inactive</option></select></div>
        <div class="col-md-3"><select class="form-select" name="parent_id"><option value="">All parents</option>@foreach($parentOptions as $parent)<option value="{{ $parent->id }}" @selected(($filters['parent_id'] ?? '') === $parent->id)>{{ $parent->name }}</option>@endforeach</select></div>
        <div class="col-md-2"><select class="form-select" name="trashed"><option value="">Active</option><option value="with" @selected(($filters['trashed'] ?? '') === 'with')>With trashed</option><option value="only" @selected(($filters['trashed'] ?? '') === 'only')>Only trashed</option></select></div>
        <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Filter</button></div>
    </form></div></div>

    <div class="row g-4">
        <div class="col-lg-8"><div class="card shadow-sm"><div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Categories</h5><a class="btn btn-primary" href="{{ route('admin.categories.create') }}">Add Category</a></div>
            <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th><a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => (($filters['direction'] ?? 'asc') === 'asc' ? 'desc' : 'asc')]) }}">Name</a></th><th>Parent</th><th>Status</th><th>Order</th><th>Children</th><th class="text-end">Actions</th></tr></thead><tbody>
            @forelse($categories as $category)
                <tr class="{{ $category->trashed() ? 'table-warning' : '' }}"><td><div class="fw-semibold">{{ $category->name }}</div><small class="text-secondary">{{ $category->slug }}</small></td><td>{{ $category->parent?->name ?? 'Root' }}</td><td><span class="badge text-bg-{{ $category->is_active ? 'success' : 'secondary' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td><td>{{ $category->sort_order }}</td><td>{{ $category->children_count }}</td><td class="text-end">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.categories.show', $category) }}">View</a>
                    @if(! $category->trashed())<a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.edit', $category) }}">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>@else<form class="d-inline" method="POST" action="{{ route('admin.categories.restore', $category) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-success">Restore</button></form><form class="d-inline" method="POST" action="{{ route('admin.categories.force-delete', $category) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete Forever</button></form>@endif
                </td></tr>
            @empty<tr><td class="text-center text-secondary py-4" colspan="6">No categories found.</td></tr>@endforelse
            </tbody></table></div><div class="card-footer">{{ $categories->links() }}</div></div></div>
        <div class="col-lg-4"><div class="card shadow-sm"><div class="card-header"><h5 class="mb-0">Tree View</h5></div><div class="card-body">@if($tree->isEmpty())<p class="text-secondary mb-0">No tree nodes yet.</p>@else @include('admin.categories._tree', ['nodes' => $tree, 'level' => 0]) @endif</div></div></div>
    </div>
</x-layouts.admin>
