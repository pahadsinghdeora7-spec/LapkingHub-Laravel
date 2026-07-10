<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class CompatibilityPolicy
{
    public function before(User $user, string $ability): ?bool { return $user->hasRole('super-admin') ? true : null; }
    public function viewAny(User $user): bool { return $user->hasPermission('manage-products'); }
    public function view(User $user, Product $product): bool { return $user->hasPermission('manage-products'); }
    public function assign(User $user, Product $product): bool { return $user->hasPermission('manage-products'); }
    public function remove(User $user, Product $product): bool { return $user->hasPermission('manage-products'); }
}
