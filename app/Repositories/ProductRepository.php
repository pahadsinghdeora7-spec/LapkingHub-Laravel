<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $product) { parent::__construct($product); }

    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()->with(['brand:id,name', 'category:id,name', 'creator:id,name', 'updater:id,name']);
        $this->applyFilters($query, $filters);
        $sort = in_array($filters['sort'] ?? '', ['name','sku','slug','price','mrp','stock_status','status','created_at','updated_at'], true) ? $filters['sort'] : 'created_at';
        $direction = ($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        return $query->orderBy($sort, $direction)->paginate((int) ($filters['per_page'] ?? 15))->withQueryString();
    }

    public function findForAdmin(string $id): Product
    {
        return $this->query()->withTrashed()->with(['brand','category','alternatePartNumbers','laptopModels.manufacturer','creator:id,name','updater:id,name'])->findOrFail($id);
    }

    public function create(array $data): Product { return $this->query()->create($data); }
    public function update(Product $product, array $data): Product { $product->update($data); return $product->refresh(); }

    public function bulk(array $ids, string $action): int
    {
        $query = $this->query()->withTrashed()->whereKey($ids);
        return match ($action) {
            'delete' => $this->query()->whereKey($ids)->delete(),
            'restore' => $query->onlyTrashed()->restore(),
            'force_delete' => $query->onlyTrashed()->forceDelete(),
            'activate' => $this->query()->whereKey($ids)->update(['status' => Product::STATUS_ACTIVE, 'is_active' => true]),
            'deactivate' => $this->query()->whereKey($ids)->update(['status' => Product::STATUS_INACTIVE, 'is_active' => false]),
            default => 0,
        };
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, fn (Builder $query, string $search) => $query->where(fn (Builder $query) => $query
            ->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%")
            ->orWhere('oem_part_number', 'like', "%{$search}%")->orWhereHas('alternatePartNumbers', fn (Builder $query) => $query->where('part_number', 'like', "%{$search}%"))));
        $query->when($filters['brand_id'] ?? null, fn (Builder $query, string $id) => $query->where('brand_id', $id));
        $query->when($filters['category_id'] ?? null, fn (Builder $query, string $id) => $query->where('category_id', $id));
        $query->when($filters['condition'] ?? null, fn (Builder $query, string $value) => $query->where('condition', $value));
        $query->when($filters['stock_status'] ?? null, fn (Builder $query, string $value) => $query->where('stock_status', $value));
        $query->when($filters['status'] ?? null, fn (Builder $query, string $value) => $query->where('status', $value));
        match ($filters['trashed'] ?? null) { 'with' => $query->withTrashed(), 'only' => $query->onlyTrashed(), default => null };
    }
}
