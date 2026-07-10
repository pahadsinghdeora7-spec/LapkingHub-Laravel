<?php

namespace App\Policies;

use App\Models\User;

class ReportPolicy
{
    public function before(User $user, string $ability): ?bool { return $user->hasRole('super-admin') ? true : null; }
    public function viewAny(User $user): bool { return $user->hasPermission('view-reports'); }
    public function view(User $user): bool { return $user->hasPermission('view-reports'); }
    public function create(User $user): bool { return $user->hasPermission('view-reports'); }
    public function update(User $user): bool { return $user->hasPermission('view-reports'); }
    public function delete(User $user): bool { return $user->hasPermission('view-reports'); }
}
