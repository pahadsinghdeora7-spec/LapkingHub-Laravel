<x-layouts.admin title="Product Compatibility | LapkingHub" page-title="Compatibility Engine">
    <div class="card shadow-sm mb-4"><div class="card-body">
        <h5 class="mb-1">{{ $product->name }}</h5><div class="text-secondary">SKU: {{ $product->sku }}</div>
    </div></div>

    <div class="card shadow-sm mb-4"><div class="card-header"><h5 class="mb-0">Search Laptop Models</h5></div><div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-3"><input class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search laptop models"></div>
            <div class="col-md-3"><select class="form-select" name="manufacturer_id"><option value="">All manufacturers</option>@foreach($manufacturers as $manufacturer)<option value="{{ $manufacturer->id }}" @selected(($filters['manufacturer_id'] ?? '') === $manufacturer->id)>{{ $manufacturer->name }}</option>@endforeach</select></div>
            <div class="col-md-3"><select class="form-select" name="series_id"><option value="">All series</option>@foreach($seriesOptions as $series)<option value="{{ $series->id }}" @selected(($filters['series_id'] ?? '') === $series->id)>{{ $series->name }}</option>@endforeach</select></div>
            <div class="col-md-2"><select class="form-select" name="laptop_model_id"><option value="">All models</option>@if($selectedLaptopModel)<option value="{{ $selectedLaptopModel->id }}" selected>{{ $selectedLaptopModel->model_name }}</option>@endif</select></div>
            <div class="col-md-1"><button class="btn btn-outline-primary w-100">Filter</button></div>
        </form>
    </div></div>

    <form method="POST" action="{{ route('admin.products.compatibilities.bulk-assign', $product) }}" class="card shadow-sm mb-4">@csrf
        <div class="card-header"><h5 class="mb-0">Assign Compatible Models</h5></div>
        <div class="table-responsive"><table class="table mb-0 align-middle"><thead><tr><th></th><th>Model</th><th>Manufacturer</th><th>Series</th><th>Status</th></tr></thead><tbody>
            @forelse($laptopModels as $model)<tr><td><input type="checkbox" name="laptop_model_ids[]" value="{{ $model->id }}"></td><td><strong>{{ $model->model_name }}</strong><br><small class="text-secondary">{{ $model->model_number ?: $model->slug }}</small></td><td>{{ $model->manufacturer?->name ?: '—' }}</td><td>{{ $model->series?->name ?: '—' }}</td><td>{{ ucfirst($model->status) }}</td></tr>
            @empty<tr><td colspan="5" class="text-center text-secondary py-4">No laptop models found.</td></tr>@endforelse
        </tbody></table></div><div class="card-footer">{{ $laptopModels->links() }}<div class="row g-3 mt-2"><div class="col-md-2"><select class="form-select" name="compatibility_type">@foreach($types as $type)<option value="{{ $type }}">{{ ucfirst($type) }}</option>@endforeach</select></div><div class="col-md-2"><input class="form-control" name="oem_part_number" placeholder="OEM part #"></div><div class="col-md-2"><input class="form-control" type="number" min="0" name="priority" value="0"></div><div class="col-md-2"><select class="form-select" name="status">@foreach($statuses as $status)<option value="{{ $status }}">{{ ucfirst($status) }}</option>@endforeach</select></div><div class="col-md-3"><input class="form-control" name="notes" placeholder="Notes"></div><div class="col-md-1"><button class="btn btn-primary w-100">Assign</button></div></div></div>
    </form>

    <form method="POST" action="{{ route('admin.products.compatibilities.bulk-remove', $product) }}" class="card shadow-sm">@csrf @method('DELETE')
        <div class="card-header"><h5 class="mb-0">Current Compatible Laptop Models</h5></div>
        <div class="table-responsive"><table class="table mb-0 align-middle"><thead><tr><th></th><th>Model</th><th>Type</th><th>OEM Part</th><th>Priority</th><th>Status</th></tr></thead><tbody>
            @forelse($compatibilities as $item)<tr><td><input type="checkbox" name="laptop_model_ids[]" value="{{ $item->laptop_model_id }}"></td><td><strong>{{ $item->laptopModel?->manufacturer?->name }} {{ $item->laptopModel?->model_name }}</strong><br><small class="text-secondary">{{ $item->laptopModel?->series?->name }}</small></td><td>{{ ucfirst($item->compatibility_type) }}</td><td>{{ $item->oem_part_number ?: '—' }}</td><td>{{ $item->priority }}</td><td>{{ ucfirst($item->status) }}</td></tr>
            @empty<tr><td colspan="6" class="text-center text-secondary py-4">No compatibility records assigned.</td></tr>@endforelse
        </tbody></table></div><div class="card-footer d-flex justify-content-between">{{ $compatibilities->links() }}<button class="btn btn-outline-danger">Bulk Remove</button></div>
    </form>
</x-layouts.admin>
