<x-layouts.auth title="Register | LapkingHub">
    <x-auth-card title="Create account" subtitle="Use a verified email and strong password.">
        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            <div class="mb-3"><label for="name" class="form-label">Name</label><input id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus autocomplete="name">@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label for="email" class="form-label">Email address</label><input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="username">@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label for="password" class="form-label">Password</label><input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-4"><label for="password_confirmation" class="form-label">Confirm password</label><input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password"></div>
            <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
        </form>
    </x-auth-card>
</x-layouts.auth>
