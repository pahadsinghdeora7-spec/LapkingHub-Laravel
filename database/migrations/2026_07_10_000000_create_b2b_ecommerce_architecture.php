<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('country', 2)->nullable()->index();
            $table->string('status')->default('active')->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'created_at']);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon_path')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['parent_id', 'is_active']);
        });

        Schema::create('sub_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['category_id', 'is_active']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('category_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('sub_category_id')->nullable()->constrained('sub_categories')->nullOnDelete();
            $table->string('sku')->unique();
            $table->string('mpn')->nullable()->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 12, 2); 
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('weight', 10, 3)->nullable();
            $table->json('dimensions')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['category_id', 'sub_category_id']);
            $table->index(['brand_id', 'is_active']);
            $table->index(['is_active', 'published_at']);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('image_name')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->unsignedBigInteger('image_size')->nullable();
            $table->unsignedInteger('image_width')->nullable();
            $table->unsignedInteger('image_height')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['product_id', 'sort_order']);
        });

        Schema::create('product_attributes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('value');
            $table->string('unit')->nullable();
            $table->boolean('is_filterable')->default(false)->index();
            $table->timestamps();
            $table->index(['product_id', 'name']);
        });

        Schema::create('compatible_models', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('brand_name')->index();
            $table->string('model_name')->index();
            $table->string('model_number')->nullable()->index();
            $table->unsignedSmallInteger('year_from')->nullable();
            $table->unsignedSmallInteger('year_to')->nullable();
            $table->timestamps();
            $table->index(['product_id', 'brand_name', 'model_name']);
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('company_name')->nullable()->index();
            $table->string('contact_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->index();
            $table->string('tax_id')->nullable()->index();
            $table->string('status')->default('active')->index();
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('shipping')->index();
            $table->string('company_name')->nullable();
            $table->string('contact_name');
            $table->string('phone')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city')->index();
            $table->string('state')->nullable()->index();
            $table->string('postal_code')->index();
            $table->string('country', 2)->index();
            $table->boolean('is_default')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['customer_id', 'type']);
        });

        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('base_rate', 10, 2)->default(0);
            $table->json('rules')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('shipping_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('status')->default('pending')->index();
            $table->string('payment_status')->default('unpaid')->index();
            $table->string('fulfillment_status')->default('unfulfilled')->index();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('shipping_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->json('billing_address');
            $table->json('shipping_address');
            $table->timestamp('placed_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['customer_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku');
            $table->string('name');
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'product_id']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->index();
            $table->string('transaction_id')->nullable()->index();
            $table->string('status')->default('pending')->index();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('type')->index();
            $table->decimal('value', 12, 2);
            $table->decimal('minimum_order_amount', 12, 2)->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('usage_limit_per_customer')->nullable();
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('expires_at')->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('order_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('discount_amount', 12, 2);
            $table->timestamp('used_at')->useCurrent()->index();
            $table->timestamps();
            $table->unique(['coupon_id', 'order_id']);
            $table->index(['coupon_id', 'customer_id']);
        });

        Schema::create('inventory', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('warehouse_code')->default('main')->index();
            $table->integer('quantity_on_hand')->default(0);
            $table->integer('quantity_reserved')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->timestamps();
            $table->unique(['product_id', 'warehouse_code']);
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('inventory_id')->constrained('inventory')->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('type')->index();
            $table->integer('quantity_change');
            $table->integer('quantity_after');
            $table->nullableUuidMorphs('reference');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['product_id', 'created_at']);
        });

        Schema::create('wishlists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['customer_id', 'product_id']);
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->unsignedInteger('quantity')->default(1);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['customer_id', 'updated_at']);
            $table->index(['session_id', 'updated_at']);
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('status')->default('pending')->index();
            $table->timestamp('approved_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['product_id', 'status']);
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image_path');
            $table->string('link_url')->nullable();
            $table->string('placement')->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('ends_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blogs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image_path')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('parent_id')->nullable()->constrained('menus')->nullOnDelete();
            $table->nullableUuidMorphs('menuable');
            $table->string('label');
            $table->string('url')->nullable();
            $table->string('location')->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['parent_id', 'location']);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('group')->index();
            $table->string('key');
            $table->json('value')->nullable();
            $table->string('type')->default('string');
            $table->boolean('is_public')->default(false)->index();
            $table->timestamps();
            $table->unique(['group', 'key']);
        });

        Schema::create('seo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('seoable');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image_path')->nullable();
            $table->json('schema_markup')->nullable();
            $table->timestamps();
            $table->unique(['seoable_type', 'seoable_id']);
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('source_url')->unique();
            $table->string('target_url');
            $table->unsignedSmallInteger('status_code')->default(301)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->nullableUuidMorphs('subject');
            $table->string('event')->index();
            $table->string('description')->nullable();
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->index(['subject_type', 'subject_id']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('seo');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('inventory');
        Schema::dropIfExists('coupon_usage');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('compatible_models');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('sub_categories');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
    }
};