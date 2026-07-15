<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table): void {
                if (! Schema::hasColumn('products', 'condition')) { $table->string('condition')->default('new')->index()->after('category_id'); }
                if (! Schema::hasColumn('products', 'warranty')) { $table->string('warranty')->nullable()->after('condition'); }
                if (! Schema::hasColumn('products', 'oem_part_number')) { $table->string('oem_part_number')->nullable()->index()->after('warranty'); }
                if (! Schema::hasColumn('products', 'hsn_code')) { $table->string('hsn_code')->nullable()->index()->after('dimensions'); }
                if (! Schema::hasColumn('products', 'gst_rate')) { $table->decimal('gst_rate', 5, 2)->default(0)->after('hsn_code'); }
                if (! Schema::hasColumn('products', 'mrp')) { $table->decimal('mrp', 12, 2)->nullable()->after('gst_rate'); }
                if (! Schema::hasColumn('products', 'minimum_order_quantity')) { $table->unsignedInteger('minimum_order_quantity')->default(1)->after('cost_price'); }
                if (! Schema::hasColumn('products', 'stock_status')) { $table->string('stock_status')->default('in_stock')->index()->after('minimum_order_quantity'); }
                if (! Schema::hasColumn('products', 'status')) { $table->string('status')->default('draft')->index()->after('stock_status'); }
                if (! Schema::hasColumn('products', 'is_trending')) { $table->boolean('is_trending')->default(false)->index()->after('is_featured'); }
                if (! Schema::hasColumn('products', 'meta_title')) { $table->string('meta_title')->nullable()->after('is_trending'); }
                if (! Schema::hasColumn('products', 'meta_description')) { $table->text('meta_description')->nullable()->after('meta_title'); }
                if (! Schema::hasColumn('products', 'meta_keywords')) { $table->text('meta_keywords')->nullable()->after('meta_description'); }
                if (! Schema::hasColumn('products', 'created_by')) { $table->foreignId('created_by')->nullable()->after('meta_keywords')->constrained('users')->nullOnDelete(); }
                if (! Schema::hasColumn('products', 'updated_by')) { $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete(); }
            });
        }

        if (! Schema::hasTable('product_alternate_part_numbers')) {
            Schema::create('product_alternate_part_numbers', function (Blueprint $table): void {
                $table->uuid('id')->primary();
                $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
                $table->string('part_number')->index();
                $table->timestamps();
                $table->unique(['product_id', 'part_number']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_alternate_part_numbers');
    }
};
