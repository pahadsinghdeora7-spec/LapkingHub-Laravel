<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(User $user, string $ability): ?bool { return $user->hasRole('super-admin') ? true : null; }
    public function viewAny(User $user): bool { return $user->hasPermission('manage-users'); }
    public function view(User $user): bool { return $user->hasPermission('manage-users'); }
    public function create(User $user): bool { return $user->hasPermission('manage-users'); }
    public function update(User $user): bool { return $user->hasPermission('manage-users'); }
    public function delete(User $user): bool { return $user->hasPermission('manage-users'); }
}
