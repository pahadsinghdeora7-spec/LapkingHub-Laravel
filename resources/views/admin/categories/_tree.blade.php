<ul class="list-unstyled ms-{{ $level ?? 0 }} mb-0">
    @foreach($nodes as $node)
        <li class="py-1">
            <span class="badge text-bg-light border me-1">{{ $node->sort_order }}</span>
            <a href="{{ route('admin.categories.show', $node) }}" class="text-decoration-none {{ $node->trashed() ? 'text-danger' : '' }}">{{ $node->name }}</a>
            <small class="text-secondary">/{{ $node->slug }}</small>
            @if($node->children->isNotEmpty())
                @include('admin.categories._tree', ['nodes' => $node->children, 'level' => ($level ?? 0) + 3])
            @endif
        </li>
    @endforeach
</ul>
