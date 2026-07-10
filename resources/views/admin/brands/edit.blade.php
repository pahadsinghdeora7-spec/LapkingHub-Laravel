<x-layouts.admin title="Edit Brand | LapkingHub" page-title="Edit Brand" :breadcrumbs="[['label' => 'Brands', 'url' => route('admin.brands.index')], ['label' => $brand->name, 'url' => route('admin.brands.show', $brand)]]">
    <div class="card shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.brands._form')
        </form>
    </div></div>
</x-layouts.admin>
