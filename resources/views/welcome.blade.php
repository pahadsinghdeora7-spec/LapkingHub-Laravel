<x-layouts.auth title="LapkingHub | Secure Account Access">
    <div class="row justify-content-center text-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <span class="badge text-bg-primary-subtle text-primary border border-primary-subtle rounded-pill mb-3">Authentication Only</span>
                    <h1 class="display-6 fw-bold mb-3">Secure LapkingHub account access</h1>
                    <p class="lead text-secondary mb-4">Register, verify your email, sign in securely, and recover your password through a clean Bootstrap interface.</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">Create account</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-4">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>
