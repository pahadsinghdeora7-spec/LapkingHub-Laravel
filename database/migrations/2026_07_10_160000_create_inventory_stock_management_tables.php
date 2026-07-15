<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('inventories')) {
            Schema::create('inventories', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
                $table->string('warehouse')->default('main')->index();
                $table->unsignedInteger('available_qty')->default(0);
                $table->unsignedInteger('reserved_qty')->default(0);
                $table->unsignedInteger('damaged_qty')->default(0);
                $table->unsignedInteger('minimum_stock')->default(0);
                $table->unsignedInteger('reorder_level')->default(0);
                $table->unsignedInteger('maximum_stock')->nullable();
                $table->string('status')->default('in_stock')->index();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
                $table->unique(['product_id', 'warehouse']);
            });
        }

        if (! Schema::hasTable('stock_movements')) {
            Schema::create('stock_movements', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('inventory_id')->constrained('inventories')->cascadeOnDelete();
                $table->string('movement_type')->index();
                $table->unsignedInteger('quantity');
                $table->unsignedInteger('previous_stock');
                $table->unsignedInteger('current_stock');
                $table->nullableUuidMorphs('reference');
                $table->text('remarks')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        } else {
            Schema::table('stock_movements', function (Blueprint $table) {
                foreach ([
                    'movement_type' => fn () => $table->string('movement_type')->nullable()->index()->after('inventory_id'),
                    'quantity' => fn () => $table->unsignedInteger('quantity')->default(0)->after('movement_type'),
                    'previous_stock' => fn () => $table->unsignedInteger('previous_stock')->default(0)->after('quantity'),
                    'current_stock' => fn () => $table->unsignedInteger('current_stock')->default(0)->after('previous_stock'),
                    'remarks' => fn () => $table->text('remarks')->nullable(),
                    'created_by' => fn () => $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('remarks'),
                ] as $column => $callback) {
                    if (! Schema::hasColumn('stock_movements', $column)) { $callback(); }
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('inventories');
    }
};
