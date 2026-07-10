<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function before(User $user, string $ability): ?bool { return $user->hasRole('super-admin') ? true : null; }
    public function viewAny(User $user): bool { return $user->hasAnyPermission(['view-customers', 'manage-customers']); }
    public function view(User $user, Customer $customer): bool { return $user->hasAnyPermission(['view-customers', 'manage-customers']); }
    public function create(User $user): bool { return $user->hasAnyPermission(['create-customers', 'manage-customers']); }
    public function update(User $user, Customer $customer): bool { return $user->hasAnyPermission(['edit-customers', 'manage-customers']); }
    public function delete(User $user, Customer $customer): bool { return $user->hasAnyPermission(['delete-customers', 'manage-customers']); }
    public function restore(User $user, Customer $customer): bool { return $user->hasAnyPermission(['delete-customers', 'manage-customers']); }
    public function forceDelete(User $user, Customer $customer): bool { return $user->hasRole('super-admin'); }
}
