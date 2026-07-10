@props(['headers' => [], 'caption' => null])

<div {{ $attributes->merge(['class' => 'table-responsive admin-table-wrapper rounded-4 border bg-body']) }}>
    <table class="table table-hover align-middle mb-0">
        @if ($caption)
            <caption class="px-3">{{ $caption }}</caption>
        @endif
        @if (count($headers))
            <thead class="table-light">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="text-uppercase small text-secondary fw-semibold">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
