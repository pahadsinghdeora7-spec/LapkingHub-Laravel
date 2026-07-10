<?php

namespace App\Policies;

use App\Models\LaptopModel;
use App\Models\User;

class LaptopModelPolicy
{
    public function before(User $user, string $ability): ?bool { return $user->hasRole('super-admin') ? true : null; }
    public function viewAny(User $user): bool { return $user->hasPermission('manage-laptop-models'); }
    public function view(User $user, LaptopModel $laptopModel): bool { return $user->hasPermission('manage-laptop-models'); }
    public function create(User $user): bool { return $user->hasPermission('manage-laptop-models'); }
    public function update(User $user, LaptopModel $laptopModel): bool { return $user->hasPermission('manage-laptop-models'); }
    public function delete(User $user, LaptopModel $laptopModel): bool { return $user->hasPermission('manage-laptop-models'); }
    public function restore(User $user, LaptopModel $laptopModel): bool { return $user->hasPermission('manage-laptop-models'); }
    public function forceDelete(User $user, LaptopModel $laptopModel): bool { return $user->hasPermission('manage-laptop-models'); }
}
