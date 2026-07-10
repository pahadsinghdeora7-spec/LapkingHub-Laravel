<?php

namespace App\Policies;

use App\Models\User;

class CustomerPolicy
{
    public function before(User $user, string $ability): ?bool { return $user->hasRole('super-admin') ? true : null; }
    public function viewAny(User $user): bool { return $user->hasPermission('manage-customers'); }
    public function view(User $user): bool { return $user->hasPermission('manage-customers'); }
    public function create(User $user): bool { return $user->hasPermission('manage-customers'); }
    public function update(User $user): bool { return $user->hasPermission('manage-customers'); }
    public function delete(User $user): bool { return $user->hasPermission('manage-customers'); }
}
