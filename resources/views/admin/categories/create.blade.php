<x-layouts.admin title="Create Category | LapkingHub" page-title="Create Category">
    <div class="card shadow-sm"><div class="card-body"><form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">@include('admin.categories._form')</form></div></div>
</x-layouts.admin>
