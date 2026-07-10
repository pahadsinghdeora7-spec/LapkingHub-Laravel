@props(['action' => '#', 'method' => 'POST', 'submit' => 'Save', 'cancel' => null])

<form action="{{ $action }}" method="{{ in_array(strtoupper($method), ['GET', 'POST']) ? $method : 'POST' }}" {{ $attributes }}>
    @csrf
    @if (! in_array(strtoupper($method), ['GET', 'POST']))
        @method($method)
    @endif

    <div class="row g-3">
        {{ $slot }}
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        @if ($cancel)
            <a href="{{ $cancel }}" class="btn btn-outline-secondary">Cancel</a>
        @endif
        <button type="submit" class="btn btn-primary">{{ $submit }}</button>
    </div>
</form>
