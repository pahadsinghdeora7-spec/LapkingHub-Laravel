<?php

namespace App\Repositories;

use App\Models\StockMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StockMovementRepository extends BaseRepository
{
    public function __construct(StockMovement $movement) { parent::__construct($movement); }
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()->with(['inventory.product:id,name,sku','creator:id,name']);
        $query->when($filters['inventory_id'] ?? null, fn ($q, $id) => $q->where('inventory_id', $id));
        $query->when($filters['movement_type'] ?? null, fn ($q, $type) => $q->where('movement_type', $type));
        return $query->latest()->paginate((int)($filters['per_page'] ?? 20))->withQueryString();
    }
    public function create(array $data): StockMovement { return $this->query()->create($data); }
}
