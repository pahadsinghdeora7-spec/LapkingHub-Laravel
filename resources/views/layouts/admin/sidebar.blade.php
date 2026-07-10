@php
    $adminMenu = [
        ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'admin.dashboard'],
        ['label' => 'Products', 'icon' => 'bi-box-seam'],
        ['label' => 'Categories', 'icon' => 'bi-tags', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*'],
        ['label' => 'Brands', 'icon' => 'bi-award', 'route' => 'admin.brands.index', 'active' => 'admin.brands.*'],
        ['label' => 'Manufacturers', 'icon' => 'bi-building', 'route' => 'admin.manufacturers.index', 'active' => 'admin.manufacturers.*'],
        ['label' => 'Series', 'icon' => 'bi-layers', 'route' => 'admin.series.index', 'active' => 'admin.series.*'],
        ['label' => 'Laptop Models', 'icon' => 'bi-laptop', 'route' => 'admin.laptop-models.index', 'active' => 'admin.laptop-models.*'],
        ['label' => 'Orders', 'icon' => 'bi-bag-check'],
        ['label' => 'Customers', 'icon' => 'bi-people'],
        ['label' => 'Inventory', 'icon' => 'bi-boxes'],
        ['label' => 'Coupons', 'icon' => 'bi-ticket-perforated'],
        ['label' => 'Marketing', 'icon' => 'bi-megaphone'],
        ['label' => 'Reports', 'icon' => 'bi-graph-up-arrow'],
        ['label' => 'SEO', 'icon' => 'bi-search'],
        ['label' => 'Settings', 'icon' => 'bi-gear'],
        ['label' => 'Users', 'icon' => 'bi-person-gear'],
        ['label' => 'Logs', 'icon' => 'bi-journal-text'],
    ];
@endphp

<aside class="admin-sidebar" id="adminSidebar" aria-label="Admin sidebar navigation">
    <div class="admin-sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <span class="brand-mark">LH</span>
            <span>
                <span class="d-block fw-bold text-body">LapkingHub</span>
                <small class="text-secondary">Enterprise Admin</small>
            </span>
        </a>
        <button class="btn btn-sm btn-light d-lg-none" type="button" data-admin-sidebar-close aria-label="Close sidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="admin-sidebar-nav">
        @foreach ($adminMenu as $item)
            @php
                $isActive = isset($item['active']) ? request()->routeIs($item['active']) : (isset($item['route']) && request()->routeIs($item['route']));
                $href = isset($item['route']) ? route($item['route']) : '#';
            @endphp
            <a href="{{ $href }}" class="admin-nav-link {{ $isActive ? 'active' : '' }}" @if(! isset($item['route'])) aria-disabled="true" @endif>
                <i class="bi {{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="admin-sidebar-card">
        <div class="small text-uppercase text-secondary fw-semibold mb-2">System</div>
        <p class="small text-secondary mb-3">Layout shell is ready for future modules without adding business logic.</p>
        <span class="badge text-bg-success-subtle text-success border border-success-subtle">UI Ready</span>
    </div>
</aside>
