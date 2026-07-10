<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\LaptopModel;
use App\Models\Manufacturer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seo;
use App\Models\Series;
use App\Models\Setting;
use App\Models\User;
use App\Policies\AdministrationPolicy;
use App\Policies\BrandPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CouponPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\InventoryPolicy;
use App\Policies\LaptopModelPolicy;
use App\Policies\ManufacturerPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SeoPolicy;
use App\Policies\SeriesPolicy;
use App\Policies\SettingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Brand::class, BrandPolicy::class);
        Gate::policy(Manufacturer::class, ManufacturerPolicy::class);
        Gate::policy(Series::class, SeriesPolicy::class);
        Gate::policy(LaptopModel::class, LaptopModelPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Inventory::class, InventoryPolicy::class);
        Gate::policy(Coupon::class, CouponPolicy::class);
        Gate::policy(Seo::class, SeoPolicy::class);
        Gate::policy(Setting::class, SettingPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        Gate::before(fn (User $user, string $ability): ?bool => $user->hasRole('super-admin') ? true : null);

        foreach ($this->permissionGates() as $ability => $method) {
            Gate::define($ability, [AdministrationPolicy::class, $method]);
        }
    }

    private function permissionGates(): array
    {
        return [
            'manage-products' => 'manageProducts',
            'manage-categories' => 'manageCategories',
            'manage-brands' => 'manageBrands',
            'manage-manufacturers' => 'manageManufacturers',
            'manage-series' => 'manageSeries',
            'manage-laptop-models' => 'manageLaptopModels',
            'manage-orders' => 'manageOrders',
            'manage-customers' => 'manageCustomers',
            'manage-inventory' => 'manageInventory',
            'manage-coupons' => 'manageCoupons',
            'manage-marketing' => 'manageMarketing',
            'manage-seo' => 'manageSeo',
            'manage-settings' => 'manageSettings',
            'manage-users' => 'manageUsers',
            'view-reports' => 'viewReports',
        ];
    }
}
