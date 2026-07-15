<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customers')) {
            return;
        }

        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'customer_code')) { $table->string('customer_code')->nullable()->unique()->after('user_id'); }
            if (! Schema::hasColumn('customers', 'business_name')) { $table->string('business_name')->nullable()->index()->after('customer_code'); }
            if (! Schema::hasColumn('customers', 'customer_name')) { $table->string('customer_name')->nullable()->index()->after('business_name'); }
            if (! Schema::hasColumn('customers', 'alternate_phone')) { $table->string('alternate_phone')->nullable()->after('phone'); }
            if (! Schema::hasColumn('customers', 'gst_number')) { $table->string('gst_number')->nullable()->index()->after('alternate_phone'); }
            if (! Schema::hasColumn('customers', 'business_type')) { $table->string('business_type')->nullable()->index()->after('gst_number'); }
            if (! Schema::hasColumn('customers', 'billing_address')) { $table->text('billing_address')->nullable()->after('business_type'); }
            if (! Schema::hasColumn('customers', 'shipping_address')) { $table->text('shipping_address')->nullable()->after('billing_address'); }
            if (! Schema::hasColumn('customers', 'city')) { $table->string('city')->nullable()->index()->after('shipping_address'); }
            if (! Schema::hasColumn('customers', 'state')) { $table->string('state')->nullable()->index()->after('city'); }
            if (! Schema::hasColumn('customers', 'country')) { $table->string('country')->nullable()->index()->after('state'); }
            if (! Schema::hasColumn('customers', 'pincode')) { $table->string('pincode')->nullable()->index()->after('country'); }
            if (! Schema::hasColumn('customers', 'available_credit')) { $table->decimal('available_credit', 12, 2)->default(0)->after('credit_limit'); }
            if (! Schema::hasColumn('customers', 'notes')) { $table->text('notes')->nullable()->after('status'); }
            if (! Schema::hasColumn('customers', 'created_by')) { $table->foreignId('created_by')->nullable()->after('notes')->constrained('users')->nullOnDelete(); }
            if (! Schema::hasColumn('customers', 'updated_by')) { $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete(); }
        });
    }

    public function down(): void
    {
        // Intentionally non-destructive for production data safety.
    }
};
