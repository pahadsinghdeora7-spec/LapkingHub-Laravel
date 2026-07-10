<?php

namespace App\Policies;

use App\Models\ProductImage;
use App\Models\User;

class ProductImagePolicy
{
    public function before(User $user, string $ability): ?bool { return $user->hasRole('super-admin') ? true : null; }
    public function viewAny(User $user): bool { return $user->hasPermission('view-product-images'); }
    public function view(User $user, ProductImage $image): bool { return $user->hasPermission('view-product-images'); }
    public function create(User $user): bool { return $user->hasPermission('create-product-images'); }
    public function update(User $user, ProductImage|string|null $image = null): bool { return $user->hasPermission('edit-product-images'); }
    public function delete(User $user, ProductImage|string|null $image = null): bool { return $user->hasPermission('delete-product-images'); }
    public function restore(User $user, ProductImage|string|null $image = null): bool { return $user->hasPermission('delete-product-images'); }
    public function forceDelete(User $user, ProductImage|string|null $image = null): bool { return $user->hasPermission('delete-product-images'); }
}
