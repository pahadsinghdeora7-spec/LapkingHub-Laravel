<x-layouts.admin title="Add Laptop Model | LapkingHub" page-title="Add Laptop Model" :breadcrumbs="[['label' => 'Laptop Models', 'url' => route('admin.laptop-models.index')]]">
    <div class="card shadow-sm"><div class="card-body"><form method="POST" action="{{ route('admin.laptop-models.store') }}">@include('admin.laptop-models._form')</form></div></div>
</x-layouts.admin>
