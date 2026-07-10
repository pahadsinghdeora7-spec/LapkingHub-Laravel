<x-layouts.admin title="Edit Category | LapkingHub" page-title="Edit Category">
    <div class="card shadow-sm"><div class="card-body"><form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">@method('PUT') @include('admin.categories._form')</form></div></div>
</x-layouts.admin>
