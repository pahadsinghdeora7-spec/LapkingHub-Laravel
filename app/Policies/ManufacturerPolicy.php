<?php

namespace App\Policies;

use App\Models\Manufacturer;
use App\Models\User;

class ManufacturerPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    public function viewAny(User $user): bool { return $user->hasPermission('manage-manufacturers'); }
    public function view(User $user, Manufacturer $manufacturer): bool { return $user->hasPermission('manage-manufacturers'); }
    public function create(User $user): bool { return $user->hasPermission('manage-manufacturers'); }
    public function update(User $user, Manufacturer $manufacturer): bool { return $user->hasPermission('manage-manufacturers'); }
    public function delete(User $user, Manufacturer $manufacturer): bool { return $user->hasPermission('manage-manufacturers'); }
    public function restore(User $user, Manufacturer $manufacturer): bool { return $user->hasPermission('manage-manufacturers'); }
    public function forceDelete(User $user, Manufacturer $manufacturer): bool { return $user->hasPermission('manage-manufacturers'); }
}
