<x-layouts.admin title="Compatible Laptop Models | LapkingHub" page-title="Compatible Laptop Models">
    <div class="card shadow-sm"><div class="card-header"><h5 class="mb-0">{{ $product->name }}</h5></div><div class="card-body">
        @forelse($groups as $manufacturer => $seriesGroups)
            <h5 class="mt-3">{{ $manufacturer }}</h5>
            @foreach($seriesGroups as $series => $items)
                <h6 class="text-secondary">{{ $series }}</h6><ul>@foreach($items as $item)<li>{{ $item->laptopModel?->model_name }} <span class="badge text-bg-light">{{ ucfirst($item->compatibility_type) }}</span></li>@endforeach</ul>
            @endforeach
        @empty<p class="text-secondary mb-0">No compatible laptop models listed for this product.</p>@endforelse
    </div></div>
</x-layouts.admin>
