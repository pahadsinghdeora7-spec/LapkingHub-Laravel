<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            'Manage Products',
            'Manage Categories',
            'Manage Brands',
            'Manage Manufacturers',
            'Manage Series',
            'Manage Laptop Models',
            'Manage Orders',
            'Manage Customers',
            'View Customers',
            'Create Customers',
            'Edit Customers',
            'Delete Customers',
            'Manage Inventory',
            'View Inventory',
            'Adjust Stock',
            'View Stock History',
            'Manage Coupons',
            'Manage Marketing',
            'Manage SEO',
            'Manage Settings',
            'Manage Users',
            'View Reports',
            'View Product Images',
            'Create Product Images',
            'Edit Product Images',
            'Delete Product Images',
        ])->mapWithKeys(fn (string $name): array => [
            Str::slug($name) => Permission::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => "Allows users to {$name}."]
            ),
        ]);

        $rolePermissions = [
            'Super Admin' => $permissions->keys()->all(),
            'Admin' => $permissions->keys()->reject(fn (string $slug): bool => $slug === 'manage-settings')->all(),
            'Manager' => ['manage-products', 'view-product-images', 'create-product-images', 'edit-product-images', 'delete-product-images', 'manage-categories', 'manage-brands', 'manage-manufacturers', 'manage-series', 'manage-laptop-models', 'manage-orders', 'manage-customers', 'view-customers', 'create-customers', 'edit-customers', 'delete-customers', 'manage-inventory', 'view-inventory', 'adjust-stock', 'view-stock-history', 'manage-coupons', 'view-reports'],
            'Sales' => ['manage-orders', 'manage-customers', 'view-customers', 'create-customers', 'edit-customers', 'manage-coupons'],
            'Warehouse' => ['manage-products', 'view-product-images', 'create-product-images', 'edit-product-images', 'delete-product-images', 'manage-inventory', 'view-inventory', 'adjust-stock', 'view-stock-history', 'manage-orders'],
            'Support' => ['manage-orders', 'manage-customers', 'view-customers', 'edit-customers'],
            'Customer' => [],
        ];

        foreach ($rolePermissions as $roleName => $permissionSlugs) {
            $role = Role::query()->updateOrCreate(
                ['slug' => Str::slug($roleName)],
                ['name' => $roleName, 'description' => "Default {$roleName} role."]
            );

            $role->permissions()->sync($permissions->only($permissionSlugs)->pluck('id')->all());
        }
    }
}
