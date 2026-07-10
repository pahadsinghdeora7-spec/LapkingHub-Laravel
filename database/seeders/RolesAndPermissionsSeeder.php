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
            'Manage Orders',
            'Manage Customers',
            'Manage Inventory',
            'Manage Coupons',
            'Manage Marketing',
            'Manage SEO',
            'Manage Settings',
            'Manage Users',
            'View Reports',
        ])->mapWithKeys(fn (string $name): array => [
            Str::slug($name) => Permission::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => "Allows users to {$name}."]
            ),
        ]);

        $rolePermissions = [
            'Super Admin' => $permissions->keys()->all(),
            'Admin' => $permissions->keys()->reject(fn (string $slug): bool => $slug === 'manage-settings')->all(),
            'Manager' => ['manage-products', 'manage-categories', 'manage-brands', 'manage-manufacturers', 'manage-orders', 'manage-customers', 'manage-inventory', 'manage-coupons', 'view-reports'],
            'Sales' => ['manage-orders', 'manage-customers', 'manage-coupons'],
            'Warehouse' => ['manage-products', 'manage-inventory', 'manage-orders'],
            'Support' => ['manage-orders', 'manage-customers'],
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
