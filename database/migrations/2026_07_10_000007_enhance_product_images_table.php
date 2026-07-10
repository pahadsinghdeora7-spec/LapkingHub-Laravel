<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table): void {
            if (Schema::hasColumn('product_images', 'path') && ! Schema::hasColumn('product_images', 'image_path')) {
                $table->renameColumn('path', 'image_path');
            }
        });

        Schema::table('product_images', function (Blueprint $table): void {
            if (! Schema::hasColumn('product_images', 'image_name')) {
                $table->string('image_name')->nullable()->after('image_path');
            }
            if (! Schema::hasColumn('product_images', 'title')) {
                $table->string('title')->nullable()->after('alt_text');
            }
            if (! Schema::hasColumn('product_images', 'image_size')) {
                $table->unsignedBigInteger('image_size')->nullable()->after('is_primary');
            }
            if (! Schema::hasColumn('product_images', 'image_width')) {
                $table->unsignedInteger('image_width')->nullable()->after('image_size');
            }
            if (! Schema::hasColumn('product_images', 'image_height')) {
                $table->unsignedInteger('image_height')->nullable()->after('image_width');
            }
            if (! Schema::hasColumn('product_images', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('image_height')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('product_images', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table): void {
            foreach (['updated_by', 'created_by', 'image_height', 'image_width', 'image_size', 'title', 'image_name'] as $column) {
                if (Schema::hasColumn('product_images', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
