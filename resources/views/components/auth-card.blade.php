@props(['title', 'subtitle' => null])

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="badge text-bg-primary-subtle text-primary border border-primary-subtle rounded-pill mb-3">Secure Access</div>
                    <h1 class="h3 fw-bold mb-2">{{ $title }}</h1>
                    @if ($subtitle)
                        <p class="text-secondary mb-0">{{ $subtitle }}</p>
                    @endif
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
