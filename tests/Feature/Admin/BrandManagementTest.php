<?php

namespace Tests\Feature\Admin;

use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_user_can_create_update_soft_delete_restore_and_force_delete_brand(): void
    {
        Storage::fake('public');
        $user = $this->manager();

        $response = $this->actingAs($user)->post(route('admin.brands.store'), [
            'name' => 'Acme Laptops',
            'logo' => UploadedFile::fake()->image('logo.png'),
            'description' => 'Enterprise laptop parts brand.',
            'website' => 'https://acme.example',
            'country' => 'us',
            'status' => Brand::STATUS_ACTIVE,
            'seo_title' => 'Acme Laptop Parts',
            'seo_description' => 'Original Acme laptop parts.',
        ]);

        $brand = Brand::query()->firstOrFail();
        $response->assertRedirect(route('admin.brands.show', $brand));
        $this->assertSame('acme-laptops', $brand->slug);
        $this->assertSame('US', $brand->country);
        $this->assertSame($user->id, $brand->created_by);
        Storage::disk('public')->assertExists($brand->logo_path);

        $this->actingAs($user)->put(route('admin.brands.update', $brand), [
            'name' => 'Acme Components',
            'slug' => 'acme-components',
            'website' => 'https://components.example',
            'country' => 'CA',
            'status' => Brand::STATUS_INACTIVE,
        ])->assertRedirect(route('admin.brands.show', $brand));

        $this->assertDatabaseHas('brands', ['id' => $brand->id, 'slug' => 'acme-components', 'status' => Brand::STATUS_INACTIVE]);

        $this->actingAs($user)->delete(route('admin.brands.destroy', $brand))->assertRedirect(route('admin.brands.index'));
        $this->assertSoftDeleted('brands', ['id' => $brand->id]);

        $this->actingAs($user)->patch(route('admin.brands.restore', $brand))->assertRedirect();
        $this->assertNotSoftDeleted('brands', ['id' => $brand->id]);

        $brand->delete();
        $this->actingAs($user)->delete(route('admin.brands.force-delete', $brand))->assertRedirect();
        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    public function test_brand_validation_rejects_invalid_payload(): void
    {
        $response = $this->actingAs($this->manager())->post(route('admin.brands.store'), [
            'name' => '',
            'website' => 'not-a-url',
            'country' => 'USA',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors(['name', 'website', 'country', 'status']);
    }

    public function test_user_without_brand_permission_is_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.brands.index'))->assertForbidden();
    }

    public function test_brand_list_supports_search_filter_sort_and_pagination(): void
    {
        $user = $this->manager();
        Brand::query()->create(['name' => 'Zenith', 'slug' => 'zenith', 'status' => Brand::STATUS_ACTIVE, 'country' => 'US']);
        Brand::query()->create(['name' => 'Omega', 'slug' => 'omega', 'status' => Brand::STATUS_INACTIVE, 'country' => 'DE']);

        $this->actingAs($user)->get(route('admin.brands.index', [
            'search' => 'Zen',
            'status' => Brand::STATUS_ACTIVE,
            'country' => 'US',
            'sort' => 'name',
            'direction' => 'asc',
            'per_page' => 1,
        ]))->assertOk()->assertSee('Zenith')->assertDontSee('Omega');
    }

    public function test_policy_allows_brand_managers_and_denies_unprivileged_users(): void
    {
        $manager = $this->manager();
        $guest = User::factory()->create();
        $brand = Brand::query()->create(['name' => 'Policy Brand', 'slug' => 'policy-brand', 'status' => Brand::STATUS_ACTIVE]);

        $this->assertTrue($manager->can('update', $brand));
        $this->assertFalse($guest->can('update', $brand));
    }

    private function manager(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'manager')->firstOrFail();
        $user->roles()->attach($role);

        return $user->fresh('roles.permissions');
    }
}
