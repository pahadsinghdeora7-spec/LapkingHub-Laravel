<x-layouts.admin title="Edit {{ $laptopModel->model_name }} | LapkingHub" page-title="Edit Laptop Model" :breadcrumbs="[['label' => 'Laptop Models', 'url' => route('admin.laptop-models.index')]]">
    <div class="card shadow-sm"><div class="card-body"><form method="POST" action="{{ route('admin.laptop-models.update', $laptopModel) }}">@method('PUT') @include('admin.laptop-models._form')</form></div></div>
</x-layouts.admin>
