<x-layouts.auth title="Dashboard | LapkingHub">
    <div class="row justify-content-center"><div class="col-lg-8"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-5 text-center"><h1 class="h3 fw-bold">Account Dashboard</h1><p class="text-secondary mb-4">You are authenticated with a verified email address.</p><form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-danger" type="submit">Logout</button></form></div></div></div></div>
</x-layouts.auth>
