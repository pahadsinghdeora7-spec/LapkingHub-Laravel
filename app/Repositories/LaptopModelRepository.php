<?php

namespace App\Repositories;

use App\Models\LaptopModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class LaptopModelRepository extends BaseRepository
{
    public function __construct(LaptopModel $laptopModel)
    {
        parent::__construct($laptopModel);
    }

    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()->with(['manufacturer:id,name', 'series:id,name', 'creator:id,name', 'updater:id,name']);
        $this->applyFilters($query, $filters);

        $sort = in_array($filters['sort'] ?? '', ['model_name', 'model_number', 'release_year', 'status', 'created_at', 'updated_at'], true) ? $filters['sort'] : 'created_at';
        $direction = ($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction)->paginate((int) ($filters['per_page'] ?? 15))->withQueryString();
    }

    public function findForAdmin(string $id): LaptopModel
    {
        return $this->query()->withTrashed()->with(['manufacturer:id,name', 'series:id,name', 'creator:id,name', 'updater:id,name'])->findOrFail($id);
    }

    public function create(array $data): LaptopModel { return $this->query()->create($data); }

    public function update(LaptopModel $laptopModel, array $data): LaptopModel
    {
        $laptopModel->update($data);
        return $laptopModel->refresh();
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function (Builder $query, string $search): void {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('model_name', 'like', "%{$search}%")
                    ->orWhere('model_number', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('manufacturer', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('series', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"));
            });
        });
        $query->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status));
        $query->when($filters['manufacturer_id'] ?? null, fn (Builder $query, string $id) => $query->where('manufacturer_id', $id));
        $query->when($filters['series_id'] ?? null, fn (Builder $query, string $id) => $query->where('series_id', $id));
        $query->when($filters['release_year'] ?? null, fn (Builder $query, string $year) => $query->where('release_year', $year));
        match ($filters['trashed'] ?? null) { 'with' => $query->withTrashed(), 'only' => $query->onlyTrashed(), default => null };
    }
}
