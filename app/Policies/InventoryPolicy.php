<?php
namespace App\Policies;
use App\Models\Inventory;
use App\Models\User;
class InventoryPolicy
{
    public function viewAny(User $user): bool { return $user->hasAnyPermission(['view-inventory','manage-inventory']); }
    public function view(User $user, ?Inventory $inventory = null): bool { return $user->hasAnyPermission(['view-inventory','manage-inventory']); }
    public function create(User $user): bool { return $user->hasPermission('manage-inventory'); }
    public function update(User $user, ?Inventory $inventory = null): bool { return $user->hasPermission('manage-inventory'); }
    public function delete(User $user, ?Inventory $inventory = null): bool { return $user->hasPermission('manage-inventory'); }
    public function adjust(User $user, ?Inventory $inventory = null): bool { return $user->hasPermission('adjust-stock'); }
}
