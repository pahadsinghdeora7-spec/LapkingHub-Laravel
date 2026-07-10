@props(['items' => [], 'current' => null])

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb admin-breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
        @foreach ($items as $label => $url)
            <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
        @endforeach
        @if ($current)
            <li class="breadcrumb-item active" aria-current="page">{{ $current }}</li>
        @endif
    </ol>
</nav>
