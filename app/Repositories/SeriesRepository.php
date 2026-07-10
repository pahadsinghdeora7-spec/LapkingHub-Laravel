<?php

namespace App\Repositories;

use App\Models\Series;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class SeriesRepository extends BaseRepository
{
    public function __construct(Series $series)
    {
        parent::__construct($series);
    }

    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()->with(['manufacturer:id,name', 'creator:id,name', 'updater:id,name']);

        $this->applyFilters($query, $filters);

        $sort = in_array($filters['sort'] ?? '', ['name', 'slug', 'status', 'created_at', 'updated_at'], true)
            ? $filters['sort']
            : 'created_at';
        $direction = ($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction)->paginate((int) ($filters['per_page'] ?? 15))->withQueryString();
    }

    public function findForAdmin(string $id): Series
    {
        return $this->query()->withTrashed()->with(['manufacturer:id,name', 'creator:id,name', 'updater:id,name'])->findOrFail($id);
    }

    public function create(array $data): Series
    {
        return $this->query()->create($data);
    }

    public function update(Series $series, array $data): Series
    {
        $series->update($data);

        return $series->refresh();
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function (Builder $query, string $search): void {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('manufacturer', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"));
            });
        });

        $query->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status));
        $query->when($filters['manufacturer_id'] ?? null, fn (Builder $query, string $manufacturerId) => $query->where('manufacturer_id', $manufacturerId));

        match ($filters['trashed'] ?? null) {
            'with' => $query->withTrashed(),
            'only' => $query->onlyTrashed(),
            default => null,
        };
    }
}
