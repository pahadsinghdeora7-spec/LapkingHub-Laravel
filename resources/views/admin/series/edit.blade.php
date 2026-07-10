<x-layouts.admin title="Edit Series | LapkingHub" page-title="Edit Series" :breadcrumbs="[['label' => 'Series', 'url' => route('admin.series.index')], ['label' => $series->name, 'url' => route('admin.series.show', $series)]]">
    <div class="card shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('admin.series.update', $series) }}">
            @method('PUT')
            @include('admin.series._form')
        </form>
    </div></div>
</x-layouts.admin>
