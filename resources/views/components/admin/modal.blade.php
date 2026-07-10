@props(['id', 'title' => 'Modal', 'size' => null, 'footer' => null])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $size ? 'modal-'.$size : '' }}">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h2 class="modal-title h5 fw-bold" id="{{ $id }}Label">{{ $title }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if ($footer)
                <div class="modal-footer border-0 pt-0">{{ $footer }}</div>
            @endif
        </div>
    </div>
</div>
