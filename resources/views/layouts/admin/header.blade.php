<header class="admin-header navbar navbar-expand bg-body border-bottom sticky-top">
    <div class="container-fluid px-3 px-lg-4">
        <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-admin-sidebar-open aria-label="Open sidebar">
            <i class="bi bi-list"></i>
        </button>

        <div>
            <div class="small text-secondary">Admin Panel</div>
            <h1 class="h5 fw-bold mb-0">{{ $pageTitle ?? 'Dashboard' }}</h1>
        </div>

        <div class="ms-auto d-flex align-items-center gap-2">
            <button class="btn btn-icon btn-outline-secondary" type="button" data-bs-toggle="tooltip" data-bs-title="Dark/light ready">
                <i class="bi bi-moon-stars"></i>
            </button>

            <div class="dropdown">
                <button class="btn btn-icon btn-outline-secondary position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-danger">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-notifications shadow border-0 p-0">
                    <div class="px-3 py-3 border-bottom">
                        <div class="fw-semibold">Notifications</div>
                        <small class="text-secondary">UI placeholders only</small>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item py-3">
                            <div class="fw-semibold small">Low stock review queued</div>
                            <small class="text-secondary">Inventory alert placeholder</small>
                        </div>
                        <div class="list-group-item py-3">
                            <div class="fw-semibold small">Marketing campaign draft</div>
                            <small class="text-secondary">Campaign reminder placeholder</small>
                        </div>
                        <div class="list-group-item py-3">
                            <div class="fw-semibold small">Security audit digest</div>
                            <small class="text-secondary">System message placeholder</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dropdown">
                <button class="btn profile-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                    <span class="d-none d-sm-block text-start">
                        <span class="d-block small fw-semibold">{{ auth()->user()->name ?? 'Admin User' }}</span>
                        <span class="d-block text-secondary small">Administrator</span>
                    </span>
                    <i class="bi bi-chevron-down small text-secondary"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow border-0">
                    <h6 class="dropdown-header">Account</h6>
                    <a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a>
                    <a class="dropdown-item" href="#"><i class="bi bi-sliders me-2"></i>Preferences</a>
                    <div class="dropdown-divider"></div>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    @else
                        <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>
