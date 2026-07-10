<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }

    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()->with(['parent:id,name', 'creator:id,name', 'updater:id,name'])->withCount('children');
        $this->applyFilters($query, $filters);

        $sort = in_array($filters['sort'] ?? '', ['name', 'slug', 'is_active', 'sort_order', 'created_at', 'updated_at'], true) ? $filters['sort'] : 'sort_order';
        $direction = ($filters['direction'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

        return $query->orderBy($sort, $direction)->orderBy('name')->paginate((int) ($filters['per_page'] ?? 15))->withQueryString();
    }

    public function tree(bool $withTrashed = false): Collection
    {
        return $this->query()
            ->when($withTrashed, fn (Builder $query) => $query->withTrashed())
            ->with(['children' => fn ($query) => $query->withTrashed()->orderBy('sort_order')->orderBy('name')->with('children')])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function options(?string $excludeId = null): Collection
    {
        return $this->query()->where('is_active', true)->when($excludeId, fn (Builder $query) => $query->whereKeyNot($excludeId))->orderBy('name')->get(['id', 'parent_id', 'name']);
    }

    public function findForAdmin(string $id): Category
    {
        return $this->query()->withTrashed()->with(['parent:id,name', 'children:id,parent_id,name,slug,is_active,sort_order', 'creator:id,name', 'updater:id,name'])->findOrFail($id);
    }

    public function create(array $data): Category { return $this->query()->create($data); }
    public function update(Category $category, array $data): Category { $category->update($data); return $category->refresh(); }

    private function applyFilters(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, fn (Builder $query, string $search) => $query->where(fn (Builder $query) => $query->where('name', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%")));
        $query->when(($filters['status'] ?? '') !== '', fn (Builder $query) => $query->where('is_active', (bool) $filters['status']));
        $query->when($filters['parent_id'] ?? null, fn (Builder $query, string $parentId) => $query->where('parent_id', $parentId));
        match ($filters['trashed'] ?? null) { 'with' => $query->withTrashed(), 'only' => $query->onlyTrashed(), default => null };
    }
}
