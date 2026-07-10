<x-layouts.admin title="Edit Manufacturer | LapkingHub" page-title="Edit Manufacturer" :breadcrumbs="[['label' => 'Manufacturers', 'url' => route('admin.manufacturers.index')], ['label' => $manufacturer->name, 'url' => route('admin.manufacturers.show', $manufacturer)]]">
    <div class="card shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('admin.manufacturers.update', $manufacturer) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.manufacturers._form')
        </form>
    </div></div>
</x-layouts.admin>
