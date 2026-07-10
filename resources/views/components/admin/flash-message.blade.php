@props(['type' => null, 'message' => null])

@php
    $flashType = $type ?? collect(['success', 'warning', 'danger', 'info'])->first(fn ($key) => session()->has($key));
    $flashMessage = $message ?? ($flashType ? session($flashType) : null);
@endphp

@if ($flashMessage)
    <div {{ $attributes->merge(['class' => 'alert alert-'.$flashType.' alert-dismissible fade show rounded-4 shadow-sm']) }} role="alert">
        {{ $flashMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
