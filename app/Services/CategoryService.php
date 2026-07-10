<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService extends BaseService
{
    public function __construct(private readonly CategoryRepository $categories) {}

    public function paginated(array $filters): LengthAwarePaginator { return $this->categories->paginated($filters); }
    public function tree(bool $withTrashed = false): Collection { return $this->categories->tree($withTrashed); }
    public function options(?string $excludeId = null): Collection { return $this->categories->options($excludeId); }
    public function findForAdmin(string $id): Category { return $this->categories->findForAdmin($id); }

    public function create(array $data, int $userId): Category
    {
        $data = $this->preparePayload($data);
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;
        $category = $this->categories->create($data);
        $this->log($category, 'created', 'Category created.', $userId);
        return $category;
    }

    public function update(Category $category, array $data, int $userId): Category
    {
        $data = $this->preparePayload($data, $category);
        $data['updated_by'] = $userId;
        $category = $this->categories->update($category, $data);
        $this->log($category, 'updated', 'Category updated.', $userId);
        return $category;
    }

    public function delete(Category $category, int $userId): void { $category->delete(); $this->log($category, 'deleted', 'Category soft deleted.', $userId); }
    public function restore(Category $category, int $userId): Category { $category->restore(); $this->log($category, 'restored', 'Category restored.', $userId); return $category->refresh(); }

    public function forceDelete(Category $category, int $userId): void
    {
        foreach (['icon_path', 'image_path'] as $path) { if ($category->{$path}) Storage::disk('public')->delete($category->{$path}); }
        $this->log($category, 'force_deleted', 'Category permanently deleted.', $userId);
        $category->forceDelete();
    }

    private function preparePayload(array $data, ?Category $category = null): array
    {
        $payload = Arr::only($data, ['parent_id', 'name', 'slug', 'description', 'is_active', 'sort_order', 'seo_title', 'seo_description']);
        $payload['parent_id'] = $payload['parent_id'] ?? null;
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);
        $payload['sort_order'] = (int) ($payload['sort_order'] ?? 0);
        $payload['slug'] = $this->uniqueSlug($payload['slug'] ?? $payload['name'], $category?->id);
        foreach (['icon' => 'icon_path', 'image' => 'image_path'] as $input => $column) {
            if (($data[$input] ?? null) instanceof UploadedFile) {
                if ($category?->{$column}) Storage::disk('public')->delete($category->{$column});
                $payload[$column] = $data[$input]->store('categories', 'public');
            }
        }
        return $payload;
    }

    private function uniqueSlug(string $value, ?string $ignoreId = null): string
    {
        $base = Str::slug($value) ?: Str::uuid()->toString(); $slug = $base; $counter = 2;
        while (Category::query()->withTrashed()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) { $slug = "{$base}-{$counter}"; $counter++; }
        return $slug;
    }

    private function log(Category $category, string $event, string $description, int $userId): void
    {
        ActivityLog::query()->create(['user_id' => $userId, 'subject_type' => $category::class, 'subject_id' => $category->id, 'event' => $event, 'description' => $description, 'properties' => ['name' => $category->name, 'slug' => $category->slug], 'ip_address' => request()?->ip(), 'user_agent' => request()?->userAgent()]);
    }
}
