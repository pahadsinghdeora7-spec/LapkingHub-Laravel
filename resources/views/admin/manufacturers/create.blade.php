<x-layouts.admin title="Add Manufacturer | LapkingHub" page-title="Add Manufacturer" :breadcrumbs="[['label' => 'Manufacturers', 'url' => route('admin.manufacturers.index')]]">
    <div class="card shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('admin.manufacturers.store') }}" enctype="multipart/form-data">
            @include('admin.manufacturers._form')
        </form>
    </div></div>
</x-layouts.admin>
