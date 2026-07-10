<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_laptop_models', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('laptop_model_id')->constrained()->cascadeOnDelete();
            $table->string('compatibility_type')->index();
            $table->string('oem_part_number')->nullable()->index();
            $table->text('notes')->nullable();
            $table->unsignedInteger('priority')->default(0)->index();
            $table->string('status')->default('active')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['product_id', 'laptop_model_id'], 'product_laptop_models_unique_pair');
            $table->index(['product_id', 'status', 'priority']);
            $table->index(['laptop_model_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_laptop_models');
    }
};
