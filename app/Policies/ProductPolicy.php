<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{
    public function before(User $user, string $ability): ?bool { return $user->hasRole('super-admin') ? true : null; }
    public function viewAny(User $user): bool { return $user->hasPermission('manage-products'); }
    public function view(User $user): bool { return $user->hasPermission('manage-products'); }
    public function create(User $user): bool { return $user->hasPermission('manage-products'); }
    public function update(User $user): bool { return $user->hasPermission('manage-products'); }
    public function delete(User $user): bool { return $user->hasPermission('manage-products'); }
    public function restore(User $user): bool { return $user->hasPermission('manage-products'); }
    public function forceDelete(User $user): bool { return $user->hasPermission('manage-products'); }
}
