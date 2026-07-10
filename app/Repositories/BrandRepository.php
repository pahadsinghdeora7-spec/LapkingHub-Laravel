<?php

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class BrandRepository extends BaseRepository
{
    public function __construct(Brand $brand)
    {
        parent::__construct($brand);
    }

    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()->with(['creator:id,name', 'updater:id,name']);

        $this->applyFilters($query, $filters);

        $sort = in_array($filters['sort'] ?? '', ['name', 'slug', 'status', 'country', 'created_at', 'updated_at'], true)
            ? $filters['sort']
            : 'created_at';
        $direction = ($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction)->paginate((int) ($filters['per_page'] ?? 15))->withQueryString();
    }

    public function findForAdmin(string $id): Brand
    {
        return $this->query()->withTrashed()->with(['creator:id,name', 'updater:id,name'])->findOrFail($id);
    }

    public function create(array $data): Brand
    {
        return $this->query()->create($data);
    }

    public function update(Brand $brand, array $data): Brand
    {
        $brand->update($data);

        return $brand->refresh();
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function (Builder $query, string $search): void {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('website', 'like', "%{$search}%");
            });
        });

        $query->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status));
        $query->when($filters['country'] ?? null, fn (Builder $query, string $country) => $query->where('country', strtoupper($country)));

        match ($filters['trashed'] ?? null) {
            'with' => $query->withTrashed(),
            'only' => $query->onlyTrashed(),
            default => null,
        };
    }
}
