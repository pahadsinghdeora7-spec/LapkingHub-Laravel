<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductLaptopModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CompatibilityRepository extends BaseRepository
{
    public function __construct(ProductLaptopModel $compatibility)
    {
        parent::__construct($compatibility);
    }

    public function paginatedForProduct(Product $product, array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()->where('product_id', $product->id)->with(['laptopModel.manufacturer:id,name', 'laptopModel.series:id,name']);
        $this->applyFilters($query, $filters);

        return $query->orderBy('priority')->orderByDesc('created_at')->paginate((int) ($filters['per_page'] ?? 15))->withQueryString();
    }

    public function laptopModelSearch(array $filters = []): LengthAwarePaginator
    {
        $query = \App\Models\LaptopModel::query()->with(['manufacturer:id,name', 'series:id,name']);
        $query->when($filters['search'] ?? null, fn (Builder $query, string $search) => $query->where(fn (Builder $query) => $query
            ->where('model_name', 'like', "%{$search}%")
            ->orWhere('model_number', 'like', "%{$search}%")
            ->orWhereHas('manufacturer', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
            ->orWhereHas('series', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))));
        $query->when($filters['manufacturer_id'] ?? null, fn (Builder $query, string $id) => $query->where('manufacturer_id', $id));
        $query->when($filters['series_id'] ?? null, fn (Builder $query, string $id) => $query->where('series_id', $id));
        $query->when($filters['laptop_model_id'] ?? null, fn (Builder $query, string $id) => $query->whereKey($id));

        return $query->orderBy('model_name')->paginate((int) ($filters['model_per_page'] ?? 10), ['*'], 'models_page')->withQueryString();
    }

    public function create(array $data): ProductLaptopModel { return $this->query()->create($data); }
    public function deleteForProduct(Product $product, array $laptopModelIds): int { return $this->query()->where('product_id', $product->id)->whereIn('laptop_model_id', $laptopModelIds)->delete(); }
    public function exists(Product $product, string $laptopModelId): bool { return $this->query()->where('product_id', $product->id)->where('laptop_model_id', $laptopModelId)->exists(); }

    public function groupedForProduct(Product $product): Collection
    {
        return $this->query()->where('product_id', $product->id)->where('status', ProductLaptopModel::STATUS_ACTIVE)
            ->with(['laptopModel.manufacturer:id,name', 'laptopModel.series:id,name'])->orderBy('priority')->get()
            ->groupBy(fn (ProductLaptopModel $item) => $item->laptopModel?->manufacturer?->name ?? 'Unknown Manufacturer')
            ->map(fn (Collection $items) => $items->groupBy(fn (ProductLaptopModel $item) => $item->laptopModel?->series?->name ?? 'Unknown Series'));
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        $query->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status));
        $query->when($filters['compatibility_type'] ?? null, fn (Builder $query, string $type) => $query->where('compatibility_type', $type));
        $query->when($filters['manufacturer_id'] ?? null, fn (Builder $query, string $id) => $query->whereHas('laptopModel', fn (Builder $query) => $query->where('manufacturer_id', $id)));
        $query->when($filters['series_id'] ?? null, fn (Builder $query, string $id) => $query->whereHas('laptopModel', fn (Builder $query) => $query->where('series_id', $id)));
        $query->when($filters['laptop_model_id'] ?? null, fn (Builder $query, string $id) => $query->where('laptop_model_id', $id));
    }
}
