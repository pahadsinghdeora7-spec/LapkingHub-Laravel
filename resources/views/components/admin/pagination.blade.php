@props(['label' => 'Showing 1 to 10 of 50 entries'])

<div {{ $attributes->merge(['class' => 'd-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center']) }}>
    <small class="text-secondary">{{ $label }}</small>
    <nav aria-label="Table pagination">
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item disabled"><span class="page-link">Previous</span></li>
            <li class="page-item active"><span class="page-link">1</span></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav>
</div>
