<?php
namespace App\Policies;
use App\Models\StockMovement;
use App\Models\User;
class StockMovementPolicy
{
    public function viewAny(User $user): bool { return $user->hasAnyPermission(['view-stock-history','manage-inventory']); }
    public function view(User $user, ?StockMovement $movement = null): bool { return $user->hasAnyPermission(['view-stock-history','manage-inventory']); }
}
