<x-layouts.admin title="Add Brand | LapkingHub" page-title="Add Brand" :breadcrumbs="[['label' => 'Brands', 'url' => route('admin.brands.index')]]">
    <div class="card shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data">
            @include('admin.brands._form')
        </form>
    </div></div>
</x-layouts.admin>
