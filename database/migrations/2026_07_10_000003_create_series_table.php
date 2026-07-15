<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('series')) {
            Schema::create('series', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('manufacturer_id')->constrained('manufacturers')->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('status')->default('active')->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['manufacturer_id', 'slug']);
            $table->index(['manufacturer_id', 'status']);
            $table->index(['status', 'created_at']);
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
