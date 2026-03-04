<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('total');
            }
            if (! Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }
            if (! Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }
            if (! Schema::hasColumn('orders', 'customer_address')) {
                $table->text('customer_address')->nullable()->after('customer_phone');
            }
            if (! Schema::hasColumn('orders', 'customer_city')) {
                $table->string('customer_city')->nullable()->after('customer_address');
            }
            if (! Schema::hasColumn('orders', 'customer_postal_code')) {
                $table->string('customer_postal_code')->nullable()->after('customer_city');
            }
            if (! Schema::hasColumn('orders', 'customer_country')) {
                $table->string('customer_country')->nullable()->after('customer_postal_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'customer_country')) {
                $table->dropColumn('customer_country');
            }
            if (Schema::hasColumn('orders', 'customer_postal_code')) {
                $table->dropColumn('customer_postal_code');
            }
            if (Schema::hasColumn('orders', 'customer_city')) {
                $table->dropColumn('customer_city');
            }
            if (Schema::hasColumn('orders', 'customer_address')) {
                $table->dropColumn('customer_address');
            }
            if (Schema::hasColumn('orders', 'customer_phone')) {
                $table->dropColumn('customer_phone');
            }
            if (Schema::hasColumn('orders', 'customer_email')) {
                $table->dropColumn('customer_email');
            }
            if (Schema::hasColumn('orders', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
        });
    }
};
