<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;

class BrandPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    public function viewAny(User $user): bool { return $user->hasPermission('manage-brands'); }
    public function view(User $user, Brand $brand): bool { return $user->hasPermission('manage-brands'); }
    public function create(User $user): bool { return $user->hasPermission('manage-brands'); }
    public function update(User $user, Brand $brand): bool { return $user->hasPermission('manage-brands'); }
    public function delete(User $user, Brand $brand): bool { return $user->hasPermission('manage-brands'); }
    public function restore(User $user, Brand $brand): bool { return $user->hasPermission('manage-brands'); }
    public function forceDelete(User $user, Brand $brand): bool { return $user->hasPermission('manage-brands'); }
}
