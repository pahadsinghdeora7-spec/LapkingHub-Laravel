<?php

namespace App\Policies;

use App\Models\User;

class AdministrationPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    public function manageProducts(User $user): bool { return $user->hasPermission('manage-products'); }
    public function manageCategories(User $user): bool { return $user->hasPermission('manage-categories'); }
    public function manageBrands(User $user): bool { return $user->hasPermission('manage-brands'); }
    public function manageManufacturers(User $user): bool { return $user->hasPermission('manage-manufacturers'); }
    public function manageOrders(User $user): bool { return $user->hasPermission('manage-orders'); }
    public function manageCustomers(User $user): bool { return $user->hasPermission('manage-customers'); }
    public function manageInventory(User $user): bool { return $user->hasPermission('manage-inventory'); }
    public function manageCoupons(User $user): bool { return $user->hasPermission('manage-coupons'); }
    public function manageMarketing(User $user): bool { return $user->hasPermission('manage-marketing'); }
    public function manageSeo(User $user): bool { return $user->hasPermission('manage-seo'); }
    public function manageSettings(User $user): bool { return $user->hasPermission('manage-settings'); }
    public function manageUsers(User $user): bool { return $user->hasPermission('manage-users'); }
    public function viewReports(User $user): bool { return $user->hasPermission('view-reports'); }
}
