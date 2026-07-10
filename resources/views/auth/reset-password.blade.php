<x-layouts.auth title="Reset Password | LapkingHub">
    <x-auth-card title="Choose a new password" subtitle="Use a strong password you do not reuse elsewhere.">
        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="mb-3"><label for="email" class="form-label">Email address</label><input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" class="form-control @error('email') is-invalid @enderror" required autofocus>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label for="password" class="form-label">New password</label><input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-4"><label for="password_confirmation" class="form-label">Confirm password</label><input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password"></div>
            <button type="submit" class="btn btn-primary w-100 py-2">Reset password</button>
        </form>
    </x-auth-card>
</x-layouts.auth>
