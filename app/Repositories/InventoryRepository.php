<?php

namespace App\Repositories;

use App\Models\Inventory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class InventoryRepository extends BaseRepository
{
    public function __construct(Inventory $inventory) { parent::__construct($inventory); }
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()->with(['product:id,name,sku,stock_status']);
        $query->when($filters['search'] ?? null, fn (Builder $q, string $s) => $q->where('warehouse','like',"%{$s}%")->orWhereHas('product', fn (Builder $p) => $p->where('name','like',"%{$s}%")->orWhere('sku','like',"%{$s}%")));
        $query->when($filters['status'] ?? null, fn (Builder $q, string $s) => $q->where('status',$s));
        $query->when($filters['warehouse'] ?? null, fn (Builder $q, string $s) => $q->where('warehouse',$s));
        $sort = in_array($filters['sort'] ?? '', ['warehouse','available_qty','reserved_qty','reorder_level','status','created_at'], true) ? $filters['sort'] : 'created_at';
        return $query->orderBy($sort, ($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc')->paginate((int)($filters['per_page'] ?? 15))->withQueryString();
    }
    public function lowStock(): Collection { return $this->query()->with('product:id,name,sku')->whereColumn('available_qty', '<=', 'reorder_level')->orWhere('status', Inventory::STATUS_LOW_STOCK)->get(); }
    public function create(array $data): Inventory { return $this->query()->create($data); }
    public function update(Inventory $inventory, array $data): Inventory { $inventory->update($data); return $inventory->refresh(); }
}
