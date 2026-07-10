<x-layouts.auth title="Forgot Password | LapkingHub">
    <x-auth-card title="Reset your password" subtitle="Enter your email and we will send a secure reset link.">
        @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4"><label for="email" class="form-label">Email address</label><input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <button type="submit" class="btn btn-primary w-100 py-2">Email reset link</button>
        </form>
    </x-auth-card>
</x-layouts.auth>
