<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_code')->nullable()->unique()->after('user_id');
            $table->string('business_name')->nullable()->index()->after('customer_code');
            $table->string('customer_name')->nullable()->index()->after('business_name');
            $table->string('alternate_phone')->nullable()->after('phone');
            $table->string('gst_number')->nullable()->index()->after('alternate_phone');
            $table->string('business_type')->nullable()->index()->after('gst_number');
            $table->text('billing_address')->nullable()->after('business_type');
            $table->text('shipping_address')->nullable()->after('billing_address');
            $table->string('city')->nullable()->index()->after('shipping_address');
            $table->string('state')->nullable()->index()->after('city');
            $table->string('country')->nullable()->index()->after('state');
            $table->string('pincode')->nullable()->index()->after('country');
            $table->decimal('available_credit', 12, 2)->default(0)->after('credit_limit');
            $table->text('notes')->nullable()->after('status');
            $table->foreignId('created_by')->nullable()->after('notes')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
        });

        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'company_name')) {
                $table->dropIndex(['company_name']);
            }
            if (Schema::hasColumn('customers', 'tax_id')) {
                $table->dropIndex(['tax_id']);
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'company_name')) {
                $table->dropColumn('company_name');
            }
            if (Schema::hasColumn('customers', 'contact_name')) {
                $table->dropColumn('contact_name');
            }
            if (Schema::hasColumn('customers', 'tax_id')) {
                $table->dropColumn('tax_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('company_name')->nullable()->index()->after('user_id');
            $table->string('contact_name')->nullable()->after('company_name');
            $table->string('tax_id')->nullable()->index()->after('phone');
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropColumn([
                'customer_code','business_name','customer_name','alternate_phone','gst_number','business_type',
                'billing_address','shipping_address','city','state','country','pincode','available_credit','notes',
            ]);
        });
    }
};
