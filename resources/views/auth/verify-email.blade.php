<x-layouts.auth title="Verify Email | LapkingHub">
    <x-auth-card title="Verify your email" subtitle="Please confirm your email before accessing your dashboard.">
        @if (session('status') === 'verification-link-sent')<div class="alert alert-success">A fresh verification link has been sent.</div>@endif
        <p class="text-secondary">Check your inbox for the verification link. If it did not arrive, request a new one below.</p>
        <div class="d-grid gap-2">
            <form method="POST" action="{{ route('verification.send') }}">@csrf<button class="btn btn-primary w-100" type="submit">Resend verification email</button></form>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-secondary w-100" type="submit">Logout</button></form>
        </div>
    </x-auth-card>
</x-layouts.auth>
