@props(['title' => null, 'subtitle' => null, 'actions' => null])

<section {{ $attributes->merge(['class' => 'card admin-card border-0 shadow-sm rounded-4']) }}>
    @if ($title || $subtitle || $actions)
        <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-start">
            <div>
                @if ($title)
                    <h2 class="h5 fw-bold mb-1">{{ $title }}</h2>
                @endif
                @if ($subtitle)
                    <p class="text-secondary mb-0">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($actions)
                <div class="card-actions">{{ $actions }}</div>
            @endif
        </div>
    @endif
    <div class="card-body p-4">
        {{ $slot }}
    </div>
</section>
