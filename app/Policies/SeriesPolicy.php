<?php

namespace App\Policies;

use App\Models\Series;
use App\Models\User;

class SeriesPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    public function viewAny(User $user): bool { return $user->hasPermission('manage-series'); }
    public function view(User $user, Series $series): bool { return $user->hasPermission('manage-series'); }
    public function create(User $user): bool { return $user->hasPermission('manage-series'); }
    public function update(User $user, Series $series): bool { return $user->hasPermission('manage-series'); }
    public function delete(User $user, Series $series): bool { return $user->hasPermission('manage-series'); }
    public function restore(User $user, Series $series): bool { return $user->hasPermission('manage-series'); }
    public function forceDelete(User $user, Series $series): bool { return $user->hasPermission('manage-series'); }
}
