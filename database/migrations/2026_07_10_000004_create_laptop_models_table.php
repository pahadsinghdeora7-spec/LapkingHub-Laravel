<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laptop_models', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('manufacturer_id')->constrained('manufacturers')->cascadeOnDelete();
            $table->foreignUuid('series_id')->constrained('series')->cascadeOnDelete();
            $table->string('model_name')->index();
            $table->string('model_number')->nullable()->index();
            $table->string('slug');
            $table->unsignedSmallInteger('release_year')->nullable()->index();
            $table->text('description')->nullable();
            $table->string('status')->default('active')->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['series_id', 'slug']);
            $table->index(['manufacturer_id', 'series_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laptop_models');
    }
};
