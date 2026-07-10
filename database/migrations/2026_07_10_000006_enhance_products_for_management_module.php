<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->string('condition')->default('new')->index()->after('category_id');
            $table->string('warranty')->nullable()->after('condition');
            $table->string('oem_part_number')->nullable()->index()->after('warranty');
            $table->string('hsn_code')->nullable()->index()->after('dimensions');
            $table->decimal('gst_rate', 5, 2)->default(0)->after('hsn_code');
            $table->decimal('mrp', 12, 2)->nullable()->after('gst_rate');
            $table->unsignedInteger('minimum_order_quantity')->default(1)->after('cost_price');
            $table->string('stock_status')->default('in_stock')->index()->after('minimum_order_quantity');
            $table->string('status')->default('draft')->index()->after('stock_status');
            $table->boolean('is_trending')->default(false)->index()->after('is_featured');
            $table->string('meta_title')->nullable()->after('is_trending');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->foreignId('created_by')->nullable()->after('meta_keywords')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->index(['status', 'stock_status']);
        });

        Schema::create('product_alternate_part_numbers', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('part_number')->index();
            $table->timestamps();
            $table->unique(['product_id', 'part_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_alternate_part_numbers');
        Schema::table('products', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropIndex(['status', 'stock_status']);
            $table->dropColumn(['condition','warranty','oem_part_number','hsn_code','gst_rate','mrp','minimum_order_quantity','stock_status','status','is_trending','meta_title','meta_description','meta_keywords']);
        });
    }
};
