<?php

namespace App\Policies;

use App\Models\User;

class MarketingPolicy
{
    public function before(User $user, string $ability): ?bool { return $user->hasRole('super-admin') ? true : null; }
    public function viewAny(User $user): bool { return $user->hasPermission('manage-marketing'); }
    public function view(User $user): bool { return $user->hasPermission('manage-marketing'); }
    public function create(User $user): bool { return $user->hasPermission('manage-marketing'); }
    public function update(User $user): bool { return $user->hasPermission('manage-marketing'); }
    public function delete(User $user): bool { return $user->hasPermission('manage-marketing'); }
}
