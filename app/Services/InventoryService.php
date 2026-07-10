<?php

namespace App\Services;

use App\Models\Inventory;
use App\Repositories\InventoryRepository;
use App\Repositories\StockMovementRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InventoryService
{
    public function __construct(private readonly InventoryRepository $inventories, private readonly StockMovementRepository $movements) {}
    public function paginated(array $filters): LengthAwarePaginator { return $this->inventories->paginated($filters); }
    public function lowStock(): Collection { return $this->inventories->lowStock(); }
    public function movements(array $filters): LengthAwarePaginator { return $this->movements->paginated($filters); }
    public function create(array $data, int $userId): Inventory { return $this->inventories->create($this->payload($data, $userId, true)); }
    public function update(Inventory $inventory, array $data, int $userId): Inventory { return $this->inventories->update($inventory, $this->payload($data, $userId)); }
    public function increaseStock(Inventory $inventory, int $quantity, int $userId, ?string $remarks = null): Inventory { return $this->changeAvailable($inventory, 'increase', $quantity, $userId, $remarks); }
    public function decreaseStock(Inventory $inventory, int $quantity, int $userId, ?string $remarks = null): Inventory { return $this->changeAvailable($inventory, 'decrease', -$quantity, $userId, $remarks); }
    public function reserveStock(Inventory $inventory, int $quantity, int $userId, ?string $remarks = null): Inventory { return $this->moveBetween($inventory, 'reserve', $quantity, $userId, $remarks, -$quantity, $quantity); }
    public function releaseStock(Inventory $inventory, int $quantity, int $userId, ?string $remarks = null): Inventory { return $this->moveBetween($inventory, 'release', $quantity, $userId, $remarks, $quantity, -$quantity); }
    public function adjustStock(Inventory $inventory, int $quantity, int $userId, ?string $remarks = null): Inventory { return $this->setAvailable($inventory, 'adjust', $quantity, $userId, $remarks); }
    public function transferStock(Inventory $from, Inventory $to, int $quantity, int $userId, ?string $remarks = null): void { DB::transaction(function () use ($from,$to,$quantity,$userId,$remarks): void { $this->decreaseStock($from,$quantity,$userId,$remarks ?? 'Transfer out'); $this->increaseStock($to,$quantity,$userId,$remarks ?? 'Transfer in'); }); }
    private function changeAvailable(Inventory $inventory, string $type, int $delta, int $userId, ?string $remarks): Inventory { return $this->setAvailable($inventory, $type, $inventory->available_qty + $delta, $userId, $remarks, abs($delta)); }
    private function moveBetween(Inventory $inventory, string $type, int $quantity, int $userId, ?string $remarks, int $availableDelta, int $reservedDelta): Inventory
    { return DB::transaction(function () use ($inventory,$type,$quantity,$userId,$remarks,$availableDelta,$reservedDelta): Inventory { $previous=$inventory->available_qty; $available=$inventory->available_qty+$availableDelta; $reserved=$inventory->reserved_qty+$reservedDelta; if ($available < 0 || $reserved < 0) throw new InvalidArgumentException('Stock quantities cannot be negative.'); $inventory->update(['available_qty'=>$available,'reserved_qty'=>$reserved,'status'=>$this->status($available,$inventory->reorder_level),'updated_by'=>$userId]); $this->record($inventory,$type,$quantity,$previous,$available,$userId,$remarks); return $inventory->refresh(); }); }
    private function setAvailable(Inventory $inventory, string $type, int $available, int $userId, ?string $remarks, ?int $quantity = null): Inventory
    { return DB::transaction(function () use ($inventory,$type,$available,$userId,$remarks,$quantity): Inventory { if ($available < 0) throw new InvalidArgumentException('Stock quantities cannot be negative.'); $previous=$inventory->available_qty; $inventory->update(['available_qty'=>$available,'status'=>$this->status($available,$inventory->reorder_level),'updated_by'=>$userId]); $this->record($inventory,$type,$quantity ?? abs($available-$previous),$previous,$available,$userId,$remarks); return $inventory->refresh(); }); }
    private function record(Inventory $inventory, string $type, int $quantity, int $previous, int $current, int $userId, ?string $remarks): void { $this->movements->create(['inventory_id'=>$inventory->id,'movement_type'=>$type,'quantity'=>$quantity,'previous_stock'=>$previous,'current_stock'=>$current,'remarks'=>$remarks,'created_by'=>$userId]); }
    private function payload(array $data, int $userId, bool $creating = false): array { $data['status'] = $this->status((int)($data['available_qty'] ?? 0), (int)($data['reorder_level'] ?? 0)); $data['updated_by']=$userId; if ($creating) $data['created_by']=$userId; return $data; }
    private function status(int $available, int $reorder): string { return $available <= 0 ? Inventory::STATUS_OUT_OF_STOCK : ($available <= $reorder ? Inventory::STATUS_LOW_STOCK : Inventory::STATUS_IN_STOCK); }
}
