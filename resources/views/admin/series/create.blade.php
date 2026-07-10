<x-layouts.admin title="Add Series | LapkingHub" page-title="Add Series" :breadcrumbs="[['label' => 'Series', 'url' => route('admin.series.index')]]">
    <div class="card shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('admin.series.store') }}">
            @include('admin.series._form')
        </form>
    </div></div>
</x-layouts.admin>
